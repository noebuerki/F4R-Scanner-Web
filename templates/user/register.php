<div class="d-flex flex-row justify-content-center mt-5">
    <div class="card bg-light mb-3">
        <div class="card-body">

            <form method="post" action="/user/doCreate" class="needs-validation" novalidate>

                <div class="form-group mb-3">
                    <input required type="text" data-toggle="tooltip" title="Enter username"
                           placeholder="Username" class="form-control" id="usernameInput" name="usernameInput"
                           maxlength="20" onfocusout="checkUsername(this.value)">
                    <p class="invalid-feedback" id="feedback">

                    </p>
                </div>

                <div class="form-group mb-3">
                    <input required type="email" data-toggle="tooltip" title="Enter e-Mmil" placeholder="E-Mail"
                           class="form-control" name="emailInput"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" maxlength="100">
                    <div class="invalid-feedback">Invalid e-mail</div>
                </div>

                <div class="form-group mb-3">
                    <input required type="password" data-toggle="tooltip" title="Enter Password"
                           placeholder="Password" class="form-control"
                           name="passwordInput" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}" maxlength="50"
                           oninput="form.passwordRepeatInput.pattern = escapeRegExp(this.value)">
                    <div class="invalid-feedback">Password invalid</div>
                </div>

                <div class="form-group mb-3">
                    <input required id="passwordRepeatInput" data-toggle="tooltip" title="Repeat password"
                           type="password" placeholder="Repeat password"
                           class="form-control" name="passwordRepeatInput" pattern="" maxlength="50">
                    <div class="invalid-feedback">Passwords don't match</div>
                </div>

                <div class="form-group form-check mb-3">
                    <input required type="checkbox" class="form-check-input" id="dataCheckbox" data-toggle="tooltip"
                           title="Confirm">
                    <label for="dataCheckbox" class="form-check-label">
                        I accept the privacy-policy
                    </label>
                </div>

                <button type="submit" class="btn btn-secondary" data-toggle="tooltip" title="Register">
                    Register <i class="bi bi-arrow-right-short"></i>
                </button>

            </form>
        </div>
    </div>
</div>

<button type="button" class="btn btn-secondary mt-5 registerspace" data-toggle="modal"
        data-target="#PWHelp" title="Show requirements">
    <i class="bi bi-clipboard-check"></i> Requirements
</button>

<div class="modal fade" id="PWHelp" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Requirements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-left">
                    Deine Benutzername darf:<br>
                    - Keine Leerzeichen enthalten<br><br>

                    Deine E-Mail muss:<br>
                    - Ein @ enthalten<br><br>

                    Dein Passwort muss:<br>
                    - Mindestens 8 Zeichen enthalten<br>
                    - Mindestens einen Kleinbuchstaben enthalten<br>
                    - Mindestens einen Grossbuchstaben enthalten<br>
                    - Mindestens eine Zahl enthalten<br>
                    - Mindesten ein Sonderzeichen enthalten<br>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="tooltip"
                        title="Hide requirements">Ok
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <a href="/user/login" class="align-self-end" data-toggle="tooltip" title="Zum Login">
        <p>Already registered?<br>Login</p>
    </a>
</div>

<script>
    /* Form Validation */
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    checkUsername(document.getElementById("usernameInput").value);
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                        Modal();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    function checkUsername(value) {
        fetch('/user/doCheckUsernameAvailable', {
            method: 'POST',
            body: JSON.stringify({Username: value}),
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            let field = document.getElementById("usernameInput")
            if (!data) {
                document.getElementById("feedback").innerText = "Username taken";
                field.setCustomValidity("Taken");
            } else if (!field.value.match(/^[^\s]+$/)) {
                document.getElementById("feedback").innerText = "Username invalid";
                field.setCustomValidity("Not matched");
            } else {
                document.getElementById("feedback").innerText = "";
                field.setCustomValidity("");
            }
        });
    }

    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

    function Modal() {
        $('#PWHelp').modal('show');
    }
</script>