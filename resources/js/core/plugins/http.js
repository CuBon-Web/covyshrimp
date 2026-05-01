import axios from 'axios';

import CONSTANTS from '../utils/constants';
const token = localStorage.getItem(CONSTANTS.ACCESS_TOKEN);

export const HTTP = axios.create({
  baseURL: __ENV__.link,
  headers: {
    Authorization: 'Bearer '+token
  }
})

HTTP.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error && error.response && error.response.status;
    if (typeof window !== "undefined") {
      const message =
        (error &&
          error.response &&
          error.response.data &&
          error.response.data.message) ||
        "";
      window.__LAST_API_ERROR__ = {
        status: status || 0,
        message: message,
        ts: Date.now()
      };
    }
    if (status === 403) {
      if (error.response && error.response.data) {
        error.response.data.message =
          error.response.data.message || 'Bạn không có quyền thực hiện thao tác này.';
      }
    }
    if (status === 401) {
      if (error.response && error.response.data) {
        error.response.data.message =
          error.response.data.message || 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.';
      }
    }
    return Promise.reject(error);
  }
);