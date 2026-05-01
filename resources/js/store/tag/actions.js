import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const listTag = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/tag/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const destroyTag = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/tag/delete/'+ opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const getInfoTag = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/tag/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveTag = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/tag/add',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};