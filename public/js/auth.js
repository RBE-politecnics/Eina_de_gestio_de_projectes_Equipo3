// Login i registre: enviem les dades a l'API amb fetch.
// Si el JavaScript falla, el formulari s'envia igualment de la manera normal.

const formulari = document.querySelector('#formAuth');

formulari.addEventListener('submit', function (event) {
    event.preventDefault();

    const dades = {};
    new FormData(formulari).forEach(function (valor, clau) {
        dades[clau] = valor;
    });

    const error = document.querySelector('#errorAuth');
    error.classList.add('d-none');

    fetch(formulari.dataset.api, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(dades)
    }).then(function (resposta) {
        if (resposta.ok) {
            window.location.href = formulari.dataset.desti;
            return;
        }

        // Si hi ha errors de validació, ensenyem el primer
        resposta.json().then(function (resultat) {
            const primer = Object.keys(resultat.errors)[0];
            error.textContent = resultat.errors[primer][0];
            error.classList.remove('d-none');
        });
    });
});
