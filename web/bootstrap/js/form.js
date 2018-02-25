$(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#trick_images');
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_image').click(function(e) {
        addImage($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    $("body").on("click", ".btn-remove", function() {
        var rel = $(this).data("rel");
        $(rel).remove();
    });

    $("body").on("change", ".upload-image", function() {
        var card = $(this).closest(".card");
            card.find(".card-img-top").remove();
        var file = URL.createObjectURL(this.files[0]);
            card.prepend('<img class="card-img-top" src="'+file+'"/>');
    });

    function addImage($container) {
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__label__" qu'il contient par le label du champ
    // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, 'Image n°' + (index+1))
        .replace(/__name__/g, index)
        ;
        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
});

      
$(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#trick_videos');
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_video').click(function(e) {
        addVideo($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    $("body").on("click", ".btn-remove", function() {
        var rel = $(this).data("rel");
        $(rel).remove();
    });
    
    $("body").on("change", ".form-control", function() {
        var card = $(this).closest(".card");
        card.find(".card-video-top").remove();
        var url = this.value;
        card.prepend('<embed class="card-video-top" src="'+url+'"/>');
    });

    function addVideo($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, 'Video n°' + (index+1))
        .replace(/__name__/g,        index)
        ;
        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
});
      