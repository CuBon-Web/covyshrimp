import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const addProject = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/project/create',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const listProject = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/project/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const deleteProject = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/project/delete/'+opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}
export const detailProject = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/project/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}