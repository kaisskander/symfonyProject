$('#add-image').click(function () {
    //je recupére le numero des futurs champs que je vais créer
    const index = +$('#widget-counter').val();
    console.log(index);

    //je recupere le prototype des entres il g means many times
    const tmpl = $('#ad2_images').data('prototype').replace(/__name__/g,index)
    //j'injecte ce code qu sein de la div
    $('#ad2_images').append(tmpl);

    $('#widget-counter').val(index+1);

    // je gere le button supprimer
    handleDeleteButtons();

}) ;

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    })

}

function updateCounter() {
    const   count =  +$('#ad2_images  div.form-group').length ;
    $('#widget-counter').val(count);

}

updateCounter();
handleDeleteButtons();