let baseUrl = 'http://localhost/admin';

async function jsonFetch(url) {
    const response = await fetch(`${baseUrl}${url}`, {
        mode: 'cors'
    });
    return await response.json();
}

export const setBaseUrl = (url) => {
    baseUrl = url;
};

export default jsonFetch;
