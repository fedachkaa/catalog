const searchGroups = function (searchParams, callback = () => {}) {
    let queryString = '';

    for (const key in searchParams) {
        if (searchParams.hasOwnProperty(key)) {
            const value = searchParams[key];
            queryString += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
        }
    }

    $.ajax({
        url: '/api/university/' + universityId + '/groups?' + queryString,
        method: 'GET',
        success: function (response) {
            callback(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

module.exports = {
    searchGroups,
};
