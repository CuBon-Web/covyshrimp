import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const listProduct = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const deleteId = ({commit},opt) => {
    console.log(opt);
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/delete/'+ opt.id_item).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const duplicateId = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/duplicate/'+ opt.id_item).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const bulkDeleteProducts = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/bulk-delete', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const bulkUpdateProductStatus = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/bulk-status', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const bulkDuplicateProducts = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/bulk-duplicate', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const editId = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveProduct = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/create',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const findTags = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/listtags/'+ opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const listVariantSku = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/list_variant_sku/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const listVariantLibrary = ({commit},opt = {}) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/variant/list', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const saveVariantLibrary = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/variant/create', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const deleteVariantLibrary = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/variant/delete/' + opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
