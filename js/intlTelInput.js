var iti;

    document.addEventListener("DOMContentLoaded", function() {
        var input = document.querySelector("#phone");
        iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: function(success, failure) {
                fetch('https://ipinfo.io/json', {
                    headers: { 'Accept': 'application/json' }
                }).then(function(response) {
                    return response.json();
                }).then(function(json) {
                    success(json.country);
                }).catch(function() {
                    success('US');
                });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });
    });

    function validatePhoneNumber() {
        if (iti.isValidNumber()) {
            var phoneNumber = iti.getNumber(); // Get number in international format
            document.querySelector("#phone").value = phoneNumber;
            return true;
        } else {
            alert("Por favor, ingrese un número de teléfono válido.");
            return false;
        }
    }
    