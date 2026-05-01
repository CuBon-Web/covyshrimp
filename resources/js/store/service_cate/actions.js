import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const listCateService = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/service/category/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const destroyCateService  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/service/category/delete/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const getInfoCateService  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/service/category/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveCategoryService = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/service/category/add',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};