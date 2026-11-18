// Tauler Kanban: arrossegar tasques, veure el detall i editar-les

const columnes = document.querySelectorAll('.columna');
let tascaArrossegada = null;

function actualitzarComptadors() {
    columnes.forEach(function (columna) {
        columna.querySelector('.comptador').textContent = columna.querySelectorAll('.tasca').length;
    });
}

function mostrarDetall(tasca) {
    document.querySelector('#detallTitol').textContent = tasca.dataset.titol;
    document.querySelector('#detallDescripcio').textContent = tasca.dataset.descripcio;
    document.querySelector('#detallTipus').textContent = tasca.dataset.tipus;
    document.querySelector('#detallResponsable').textContent = tasca.dataset.responsable;
    document.querySelector('#detallData').textContent = tasca.dataset.data;

    // El nom de l'estat el té la columna amb el mateix data-estat que la tasca
    const columna = document.querySelector('.columna[data-estat="' + tasca.dataset.estat + '"]');
    document.querySelector('#detallEstat').textContent = columna.dataset.nom;

    const avis = document.querySelector('#detallBloqueig');
    const sensePermis = document.querySelector('#detallSensePermis');
    const zonaEditar = document.querySelector('#zonaEditar');

    avis.classList.add('d-none');
    sensePermis.classList.add('d-none');
    zonaEditar.classList.add('d-none');

    if (tasca.dataset.estat == 'feta') {
        avis.classList.remove('d-none');
    } else if (tasca.dataset.pot == '0') {
        sensePermis.classList.remove('d-none');
    } else {
        // Omplim el formulari d'edició amb les dades de la tasca
        const formulari = document.querySelector('#formEditarTasca');
        formulari.action = formulari.dataset.base + '/' + tasca.dataset.id;
        document.querySelector('#editTitol').value = tasca.dataset.titol;
        document.querySelector('#editDescripcio').value = tasca.dataset.descripcio;
        document.querySelector('#editEstat').value = tasca.dataset.estatId;
        document.querySelector('#editData').value = tasca.dataset.dataIso;

        document.querySelector('#formEliminarTasca').action = formulari.dataset.base + '/' + tasca.dataset.id;
        zonaEditar.classList.remove('d-none');
    }
}

function prepararTasca(tasca) {
    tasca.addEventListener('dragstart', function () {
        tascaArrossegada = tasca;
    });

    tasca.addEventListener('click', function () {
        mostrarDetall(tasca);
    });
}

document.querySelectorAll('.tasca').forEach(function (tasca) {
    prepararTasca(tasca);
});

columnes.forEach(function (columna) {
    // Sense preventDefault el navegador no deixa deixar anar res a sobre
    columna.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    columna.addEventListener('drop', function (event) {
        event.preventDefault();

        // Una tasca feta no es pot tornar a moure
        if (tascaArrossegada == null || tascaArrossegada.dataset.estat == 'feta') {
            return;
        }

        // Si la deixem a la mateixa columna no cal fer res
        if (tascaArrossegada.dataset.estat == columna.dataset.estat) {
            tascaArrossegada = null;
            return;
        }

        // Enviem el nou estat al servidor amb el formulari amagat
        const formulari = document.querySelector('#formMoure');
        formulari.action = formulari.dataset.base + '/' + tascaArrossegada.dataset.id;
        document.querySelector('#moureEstat').value = columna.dataset.estatId;
        formulari.submit();
    });
});

actualitzarComptadors();
