function accueil() {
    const url = 'index.php?route=Badge';

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Success:', data);
            // Vous pouvez ajouter du code ici pour manipuler le DOM avec les données reçues
            afficheBadges(data);
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

    const newBadgeButton = document.getElementById('newBadgeButton');
    newBadgeButton.addEventListener('click', () => {
        openModalNewBadge(null);
    });
}

function afficheBadges(jsonData) {
    const mainDiv = document.getElementById('main');

    // Créer le tableau
    const table = document.createElement('table');
    table.border = "1"; // Optionnel, pour ajouter une bordure au tableau

    // Créer l'entête du tableau
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');

    // Ajouter les noms des propriétés comme en-têtes de colonne
    const keys = Object.keys(jsonData[0]);
    keys.forEach(key => {
        const th = document.createElement('th');
        th.textContent = key;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Créer le corps du tableau
    const tbody = document.createElement('tbody');

    jsonData.forEach(item => {
        const row = document.createElement('tr');
        keys.forEach(key => {
            const td = document.createElement('td');
            td.textContent = item[key];
            row.appendChild(td);
        });

        // Rajout de la colonne pour le bouton "Voir"
        const actionTd = document.createElement('td');
        const button = document.createElement('button');
        button.textContent = 'Voir';
        button.addEventListener('click', () => {
            fetch(`index.php?route=Badge&id=${item.id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    openModalViewBadge(data);
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        });

        actionTd.appendChild(button);
        row.appendChild(actionTd);

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    mainDiv.appendChild(table);

    function openModalViewBadge(data) {
        const modal = document.getElementById('myModal');
        const modalFormContainer = document.getElementById('modalFormContainer');
        // Effacer le contenu précédent du formulaire modal
        while (modalFormContainer.firstChild) {
            modalFormContainer.removeChild(modalFormContainer.firstChild);
        }

        // Créer le formulaire et ajouter les éléments
        const form = document.createElement('form');

        const labelId = document.createElement('label');
        labelId.setAttribute('for', 'modalId');
        labelId.textContent = 'ID:';
        form.appendChild(labelId);

        const inputId = document.createElement('input');
        inputId.type = 'text';
        inputId.id = 'modalId';
        inputId.name = 'id';
        inputId.value = data.id;
        inputId.readOnly = true;
        form.appendChild(inputId);

        form.appendChild(document.createElement('br'));

        const labelBadge = document.createElement('label');
        labelBadge.setAttribute('for', 'modalBadge');
        labelBadge.textContent = 'Badge:';
        form.appendChild(labelBadge);

        const inputBadge = document.createElement('input');
        inputBadge.type = 'text';
        inputBadge.id = 'modalBadge';
        inputBadge.name = 'badge';
        inputBadge.value = data.badge || 'N/A';
        inputBadge.readOnly = true;
        form.appendChild(inputBadge);

        form.appendChild(document.createElement('br'));

        const labelNom = document.createElement('label');
        labelNom.setAttribute('for', 'modalNom');
        labelNom.textContent = 'Nom:';
        form.appendChild(labelNom);

        const inputNom = document.createElement('input');
        inputNom.type = 'text';
        inputNom.id = 'modalNom';
        inputNom.name = 'nom';
        inputNom.value = data.nom || 'N/A';
        inputNom.readOnly = true;
        form.appendChild(inputNom);

        form.appendChild(document.createElement('br'));

        modalFormContainer.appendChild(form);
        modal.style.display = "block";
    }

    // Fermer la modale
    const modal = document.getElementById('myModal');
    const span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function openModalNewBadge(data) {
    const modal = document.getElementById('myModal');
    const modalFormContainer = document.getElementById('modalFormContainer');
    // Effacer le contenu précédent du formulaire modal
    while (modalFormContainer.firstChild) {
        modalFormContainer.removeChild(modalFormContainer.firstChild);
    }

    // Créer le formulaire et ajouter les éléments
    const form = document.createElement('form');
    form.id = 'badgeForm';

    const labelBadge = document.createElement('label');
    labelBadge.setAttribute('for', 'badge');
    labelBadge.textContent = 'Badge:';
    form.appendChild(labelBadge);

    const inputBadge = document.createElement('input');
    inputBadge.type = 'text';
    inputBadge.id = 'badge';
    inputBadge.name = 'badge';
    form.appendChild(inputBadge);

    form.appendChild(document.createElement('br'));

    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = 'Ajouter';
    form.appendChild(submitButton);

    const route = document.createElement('input');
    route.type = 'hidden';
    route.id = 'route';
    route.name = 'route';
    route.value = 'Badge';
    form.appendChild(route);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new URLSearchParams(new FormData(form)).toString();
        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(newData => {
            addTableRow(newData);
            modal.style.display = "none";
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    });

    modalFormContainer.appendChild(form);
    modal.style.display = "block";
}

function addTableRow(data) {
    const table = document.querySelector('#main table tbody');
    const row = document.createElement('tr');

    const keys = Object.keys(data);
    keys.forEach(key => {
        const td = document.createElement('td');
        td.textContent = data[key];
        row.appendChild(td);
    });

    const actionTd = document.createElement('td');
    const button = document.createElement('button');
    button.textContent = 'Voir';
    button.addEventListener('click', () => {
        fetch(`index.php?route=Badge&id=${data.id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                openModalViewBadge(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    });

    actionTd.appendChild(button);
    row.appendChild(actionTd);

    table.appendChild(row);
}
