function refreshCsrfToken() {
    fetch("/refresh-csrf")
        .then((res) => res.json())
        .then((data) => {
            if (data.csrf_token) {
                document
                    .querySelector('meta[name="csrf-token"]')
                    .setAttribute("content", data.csrf_token);

                // If using jQuery
                if (window.$) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": data.csrf_token,
                        },
                    });
                }
            }
        });
}

// Refresh every 10 minutes (600,000 ms)
setInterval(refreshCsrfToken, 600000);
