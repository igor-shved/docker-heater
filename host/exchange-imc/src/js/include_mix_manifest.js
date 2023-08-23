fetch('../mix-manifest.json')
    .then(response => response.json())
    .then(data => {
        Object.entries(data).map(([key, value]) => {
            document.querySelectorAll('link[href="' + key + '"]').forEach(element => {
                element.setAttribute('href', value);
            });
            document.querySelectorAll('script[src="' + key + '"]').forEach(element => {
                element.setAttribute('src', value);
            });
        })
    })
    .catch(error => {
        console.error('error open mix-manifest.json', error);
    })