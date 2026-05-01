<template>
  <crm-form-section title="SEO Assistant">
    <div class="seo-score-wrap">
      <div class="seo-score-value">{{ score }}</div>
      <div>
        <p class="seo-score-label">SEO score</p>
        <p class="seo-score-note">{{ scoreMessage }}</p>
      </div>
    </div>
    <div class="form-group">
      <label>Từ khóa chính</label>
      <vs-input
        class="w-100"
        placeholder="Từ khóa bạn muốn đẩy SEO"
        :value="model.focusKeyword"
        @input="updateField('focusKeyword', $event)"
      />
    </div>
    <div class="seo-keyword-suggest">
      <p class="seo-checklist-title">Từ khóa gợi ý</p>
      <div class="seo-keyword-list">
        <div v-for="keyword in suggestedKeywords" :key="keyword" class="seo-keyword-item">
          <span class="seo-keyword-chip" @click="applyKeyword(keyword)">{{ keyword }}</span>
          <el-button size="mini" @click="insertKeyword('title', keyword)">Tiêu đề</el-button>
          <el-button size="mini" @click="insertKeyword('description', keyword)">Mô tả</el-button>
        </div>
      </div>
      <small class="seo-muted">Bấm vào từ khóa để dùng làm từ khóa chính, hoặc chèn vào tiêu đề/mô tả.</small>
    </div>

    <div class="form-group">
      <label>SEO title</label>
      <vs-input
        class="w-100"
        placeholder="Tiêu đề hiển thị trên Google"
        :value="model.seoTitle"
        @input="updateField('seoTitle', $event)"
      />
      <small class="seo-muted">{{ titleLength }}/60 ký tự</small>
    </div>
    <div class="form-group">
      <label>Meta description</label>
      <vs-textarea
        class="w-100"
        :value="model.metaDescription"
        @input="updateField('metaDescription', $event)"
      />
      <small class="seo-muted">{{ metaLength }}/160 ký tự</small>
    </div>
    <div class="form-group">
      <label>Slug</label>
      <vs-input
        class="w-100"
        placeholder="duong-dan-bai-viet"
        :value="model.slug"
        @input="updateField('slug', $event)"
      />
    </div>

    <div class="seo-actions">
      <el-button size="mini" @click="$emit('auto-optimize')">Tối ưu nhanh</el-button>
    </div>

    <div class="seo-checklist">
      <p class="seo-checklist-title">Checklist</p>
      <div v-for="item in checklist" :key="item.key" class="seo-check-item">
        <i :class="item.ok ? 'el-icon-success seo-ok' : 'el-icon-warning seo-warn'"></i>
        <span>{{ item.label }}</span>
      </div>
    </div>

    <div class="seo-preview">
      <p class="seo-preview-title">{{ previewTitle }}</p>
      <p class="seo-preview-url">{{ previewUrl }}</p>
      <p class="seo-preview-desc">{{ previewDescription }}</p>
    </div>
  </crm-form-section>
</template>

<script>
export default {
  name: "seo-assistant",
  props: {
    model: {
      type: Object,
      required: true
    },
    articleTitle: {
      type: String,
      default: ""
    },
    articleContent: {
      type: String,
      default: ""
    },
    articleDescription: {
      type: String,
      default: ""
    },
    previewPath: {
      type: String,
      default: "blogs"
    }
  },
  computed: {
    titleLength() {
      return (this.model.seoTitle || "").trim().length;
    },
    metaLength() {
      return (this.model.metaDescription || "").trim().length;
    },
    plainContent() {
      return (this.articleContent || "").replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim();
    },
    contentWordCount() {
      return this.plainContent.split(" ").filter(Boolean).length;
    },
    headingCount() {
      const matches = (this.articleContent || "").match(/<h2\b[^>]*>/gi);
      return matches ? matches.length : 0;
    },
    imageCount() {
      const matches = (this.articleContent || "").match(/<img\b[^>]*>/gi);
      return matches ? matches.length : 0;
    },
    imageAltCount() {
      const matches = (this.articleContent || "").match(/<img\b[^>]*\salt\s*=\s*["'][^"']+["'][^>]*>/gi);
      return matches ? matches.length : 0;
    },
    internalLinkCount() {
      const html = this.articleContent || "";
      const matches = html.match(/<a\b[^>]*href\s*=\s*["']([^"']+)["'][^>]*>/gi) || [];
      const base =
        (typeof __ENV__ !== "undefined" && __ENV__.link) ||
        (typeof window !== "undefined" && window.location && window.location.origin) ||
        "";
      const normalizedBase = (base || "").replace(/\/+$/, "");
      return matches.reduce((count, tag) => {
        const hrefMatch = tag.match(/href\s*=\s*["']([^"']+)["']/i);
        if (!hrefMatch) return count;
        const href = (hrefMatch[1] || "").trim().toLowerCase();
        const isInternal =
          href.startsWith("/") ||
          href.startsWith("#") ||
          (normalizedBase && href.startsWith(normalizedBase.toLowerCase()));
        return isInternal ? count + 1 : count;
      }, 0);
    },
    keywordDensityPercent() {
      const keyword = this.normalizeForMatch(this.model.focusKeyword || this.derivedKeyword || "");
      if (!keyword || !this.plainContent) return 0;
      const tokens = this.normalizeForMatch(this.plainContent).split(" ").filter(Boolean);
      if (!tokens.length) return 0;
      const keywordTokens = keyword.split(" ").filter(Boolean);
      if (!keywordTokens.length) return 0;
      let hits = 0;
      for (let i = 0; i <= tokens.length - keywordTokens.length; i += 1) {
        const phrase = tokens.slice(i, i + keywordTokens.length).join(" ");
        if (phrase === keyword) {
          hits += 1;
        }
      }
      return Number(((hits / tokens.length) * 100).toFixed(2));
    },
    checklist() {
      const keyword = this.normalizeForMatch(this.model.focusKeyword || this.derivedKeyword || "");
      const seoTitle = (this.model.seoTitle || "").trim();
      const metaDescription = (this.model.metaDescription || "").trim();
      const slug = (this.model.slug || "").trim();
      const normalizedTitle = this.normalizeForMatch(seoTitle);
      const normalizedMeta = this.normalizeForMatch(metaDescription);
      const hasKeywordInTitle = keyword && normalizedTitle.includes(keyword);
      const hasKeywordInMeta = keyword && normalizedMeta.includes(keyword);
      const hasGoodKeywordDensity = this.keywordDensityPercent >= 0.5 && this.keywordDensityPercent <= 3;
      const hasImageAltCoverage = this.imageCount === 0 || this.imageAltCount >= this.imageCount;
      return [
        { key: "title-len", label: "SEO title 45-60 ký tự", ok: this.titleLength >= 45 && this.titleLength <= 60 },
        { key: "meta-len", label: "Meta description 120-160 ký tự", ok: this.metaLength >= 120 && this.metaLength <= 160 },
        { key: "slug", label: "Slug ngắn, không dấu, có dấu '-'", ok: !!slug && !/[A-Z_\s]/.test(slug) },
        { key: "keyword-title", label: "Từ khóa chính có trong SEO title", ok: !!hasKeywordInTitle },
        { key: "keyword-meta", label: "Từ khóa chính có trong meta description", ok: !!hasKeywordInMeta },
        { key: "content-len", label: "Nội dung đủ dài (>300 từ)", ok: this.contentWordCount >= 300 },
        { key: "keyword-density", label: "Mật độ từ khóa chính trong khoảng 0.5% - 3%", ok: hasGoodKeywordDensity },
        { key: "h2", label: "Có ít nhất 1 tiêu đề H2 trong nội dung", ok: this.headingCount > 0 },
        { key: "internal-link", label: "Có ít nhất 1 internal link", ok: this.internalLinkCount > 0 },
        { key: "img-alt", label: "Ảnh có thuộc tính alt", ok: hasImageAltCoverage }
      ];
    },
    derivedKeyword() {
      return (this.articleTitle || "")
        .replace(/<[^>]+>/g, " ")
        .replace(/[^a-zA-Z0-9\u00C0-\u024F\s]/g, " ")
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 4)
        .join(" ");
    },
    suggestedKeywords() {
      const source = `${this.articleTitle || ""} ${this.articleDescription || ""}`;
      const preserved = source
        .replace(/<[^>]+>/g, " ")
        .replace(/[^\p{L}\p{N}\s]/gu, " ")
        .replace(/\s+/g, " ")
        .trim();
      const normalized = this.normalizeForMatch(source);
      const stopWords = [
        "va", "la", "cho", "voi", "cua", "nhung", "mot", "cac", "trong", "khi", "tai", "den",
        "the", "is", "are", "to", "for", "with", "from", "that", "this", "have", "has"
      ];
      const tokens = normalized
        .split(" ")
        .filter(word => word.length > 2 && !stopWords.includes(word));
      const preservedTokens = preserved
        .split(" ")
        .filter(word => word && word.length > 1);
      const mapPreserved = {};
      preservedTokens.forEach(word => {
        const key = this.normalizeForMatch(word);
        if (key && !mapPreserved[key]) {
          mapPreserved[key] = word;
        }
      });
      const scoreMap = {};
      tokens.forEach((word, index) => {
        scoreMap[word] = (scoreMap[word] || 0) + 1;
        if (tokens[index + 1]) {
          const phrase = `${word} ${tokens[index + 1]}`;
          scoreMap[phrase] = (scoreMap[phrase] || 0) + 1.5;
        }
      });
      const sorted = Object.entries(scoreMap)
        .sort((a, b) => b[1] - a[1])
        .map(item => item[0].split(" ").map(part => mapPreserved[part] || part).join(" "))
        .filter(item => item.length >= 4);
      const unique = [...new Set(sorted)];
      return unique.slice(0, 6);
    },
    score() {
      const okCount = this.checklist.filter(item => item.ok).length;
      return Math.round((okCount / this.checklist.length) * 100);
    },
    scoreMessage() {
      if (this.score >= 85) return "Rất tốt, sẵn sàng publish";
      if (this.score >= 65) return "Ổn, nên tối ưu thêm";
      return "Cần cải thiện thêm";
    },
    previewTitle() {
      return (this.model.seoTitle || this.articleTitle || "Tiêu đề bài viết").trim();
    },
    previewDescription() {
      const source = (this.model.metaDescription || this.articleDescription || "").trim();
      return source || "Mô tả bài viết sẽ hiển thị ở đây.";
    },
    previewUrl() {
      const slug = (this.model.slug || "slug-bai-viet").trim();
      const base =
        (typeof __ENV__ !== "undefined" && __ENV__.link) ||
        (typeof window !== "undefined" && window.location && window.location.origin) ||
        "";
      const normalizedBase = (base || "").replace(/\/+$/, "");
      const normalizedPath = (this.previewPath || "").replace(/^\/+|\/+$/g, "");
      return `${normalizedBase}/${normalizedPath}/${slug}`;
    }
  },
  methods: {
    updateField(field, value) {
      this.$emit("update:model", { ...this.model, [field]: value });
    },
    normalizeForMatch(value) {
      return (value || "")
        .toString()
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d")
        .replace(/[^a-z0-9\s]/g, " ")
        .replace(/\s+/g, " ")
        .trim();
    },
    applyKeyword(keyword) {
      this.updateField("focusKeyword", keyword);
    },
    insertKeyword(target, keyword) {
      this.$emit("insert-keyword", { target, keyword });
    }
  }
};
</script>

<style scoped>
.seo-score-wrap {
  display: flex;
  gap: 10px;
  align-items: center;
  margin-bottom: 12px;
}
.seo-score-value {
  width: 46px;
  height: 46px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #eef2ff;
  color: #2f44a0;
  font-weight: 700;
}
.seo-score-label {
  margin: 0;
  font-weight: 600;
}
.seo-score-note {
  margin: 0;
  color: #6a7490;
  font-size: 12px;
}
.seo-muted {
  color: #6a7490;
}
.seo-actions {
  margin-bottom: 10px;
}
.seo-checklist {
  border: 1px dashed #dce3f5;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 10px;
}
.seo-checklist-title {
  margin: 0 0 6px;
  font-weight: 600;
}
.seo-keyword-suggest {
  margin-bottom: 12px;
}
.seo-keyword-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.seo-keyword-item {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}
.seo-keyword-chip {
  display: inline-flex;
  padding: 4px 10px;
  border-radius: 999px;
  border: 1px solid #cfd9f2;
  color: #253a8e;
  background: #f3f6ff;
  cursor: pointer;
  font-size: 12px;
}
.seo-check-item {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 5px;
  font-size: 12px;
}
.seo-ok {
  color: #2e7d32;
}
.seo-warn {
  color: #ed6c02;
}
.seo-preview {
  border: 1px solid #e4e9f6;
  border-radius: 8px;
  padding: 10px;
  background: #fff;
}
.seo-preview-title {
  margin: 0 0 4px;
  color: #1a0dab;
  font-size: 16px;
}
.seo-preview-url {
  margin: 0 0 4px;
  color: #0a7a35;
  font-size: 12px;
}
.seo-preview-desc {
  margin: 0;
  color: #4d5156;
  font-size: 13px;
}
</style>
