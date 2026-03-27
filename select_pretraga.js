function filtrirajSelect(inputId, selectId) {
    let input = document.getElementById(inputId);
    let filter = input.value.trim().toLowerCase();

    let select = document.getElementById(selectId);

    if (!select.dataset.originalOptions) {
        select.dataset.originalOptions = select.innerHTML;
    }

    let tempSelect = document.createElement("select");
    tempSelect.innerHTML = select.dataset.originalOptions;

    let sveOpcije = tempSelect.getElementsByTagName("option");

    let trenutnoIzabrano = select.value;

    select.innerHTML = "";

    for (let i = 0; i < sveOpcije.length; i++) {
        let opcija = sveOpcije[i];
        let tekst = opcija.text.trim().toLowerCase();

        if (i === 0 || filter === "" || tekst.startsWith(filter)) {
            let novaOpcija = opcija.cloneNode(true);
            select.appendChild(novaOpcija);
        }
    }

    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === trenutnoIzabrano) {
            select.selectedIndex = i;
            break;
        }
    }
}