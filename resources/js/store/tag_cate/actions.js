import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const listTagCate = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/tag/category/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const destroyTagCate  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/tag/category/delete/'+ opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const getInfoTagCate  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/tag/category/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveTagCate = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/tag/category/add',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const findTagCate = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/tag/category/findCateType/'+ opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};