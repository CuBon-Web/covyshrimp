import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const addSolution = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/solution/create',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const listSolution = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/solution/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const deleteSolution = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/solution/delete/'+opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}
export const detailSolution = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/solution/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}