import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';

export const destroyToken = (context, opts) => {
    if(context.getters.isLoggedIn){
        return new Promise((resolve, reject) => {
            HTTP.post('/api/logout').then(response => {
                localStorage.removeItem(CONSTANTS.ACCESS_TOKEN);
                context.commit('DESTROY_TOKEN');
                return resolve(response);
            }).catch(error => {
                localStorage.removeItem(CONSTANTS.ACCESS_TOKEN);
                context.commit('DESTROY_TOKEN');
                return reject(error);
            })
        })
    }
    
};
export const retrieveToken = ({commit}, opts) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/login',opts).then((response) => {
            const token = response.data.data.access_token;
            commit('RETRIEVE_TOKEN', token);
            commit("CURREN_USER", response.data.data.user);
            commit("LOGGED_IN", true);
            if (typeof (localStorage) !== 'undefined') {
                localStorage.setItem(CONSTANTS.ACCESS_TOKEN, token);
            }
            return resolve(response);
        }).catch(error => {
            commit("LOGGED_IN_ERROR");
            return reject(error)
        })
    })
};
export const getNotification = ({commit}, opts) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/getNotification').then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const profileUserCms = ({commit}, opts) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/profile').then(response => {
            commit("CURREN_USER", response.data);
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const updateProfileCms = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/profile/update', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const changePasswordCms = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/profile/change-password', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const listAdminUsers = ({commit}) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/admin-user/list').then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const createAdminUser = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/admin-user/create', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const resetAdminUserPassword = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/admin-user/reset-password/' + opt.id, opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const updateAdminUserRole = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/admin-user/update-role/' + opt.id, opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    })
};
export const listPermissions = ({commit}) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/rbac/permissions').then(response => resolve(response.data)).catch(reject);
    })
};
export const createPermission = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/rbac/permission/create', opt).then(response => resolve(response.data)).catch(reject);
    })
};
export const listRoles = ({commit}) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/rbac/roles').then(response => resolve(response.data)).catch(reject);
    })
};
export const createRoleRbac = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/rbac/role/create', opt).then(response => resolve(response.data)).catch(reject);
    })
};
export const updateRolePermissions = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/rbac/role/update-permissions/' + opt.id, opt).then(response => resolve(response.data)).catch(reject);
    })
};
export const listAdminUsersRbac = ({commit}) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/rbac/admin-users').then(response => resolve(response.data)).catch(reject);
    })
};
export const assignRolesToAdminUser = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/rbac/admin-users/assign-roles/' + opt.id, opt).then(response => resolve(response.data)).catch(reject);
    })
};


