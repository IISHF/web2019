export async function api(url, method = 'POST', options = {}) {
    const response = await fetch(
        url,
        Object.assign(
            {
                cache: 'no-cache',
                credentials: 'same-origin',
                mode: 'same-origin',
            },
            options,
            {method}
        )
    );
    if (!response.ok) {
        throw new Error('Response was not ok.');
    }

    return response;
}

const apiMethods = {
    get: async (url, options = {}) => {
        return await api(url, 'GET', options);
    },
    post: async (url, body, options = {}) => {
        return await api(url, 'POST', Object.assign({}, options, {body}));
    },
    put: async (url, body, options = {}) => {
        return await api(url, 'PUT', Object.assign({}, options, {body}));
    },
    delete: async (url, options = {}) => {
        return await api(url, 'DELETE', options);
    }
};

export default apiMethods;
