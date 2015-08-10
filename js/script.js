$(document).ready(function() {

    $("#register input").keyup(function() {
        $.post("ajax/registerpost.php", $("#register").serialize(), function(data) {
            $("#return").html(data);
        });
    });

    $("#submitreg").click(function() {
        //if user clicks submit, check for submit value, if there already is one present, post. If there isn't, create one and then post.
        if ($("#submit").length === 0) {

            $("#register").append('<input name="submit" id="submit" type="hidden">');
            $.post("ajax/registerpost.php", $("#register").serialize(), function(data) {
                $("#return").html(data);
            });
        } else {
            $.post("ajax/registerpost.php", $("#register").serialize(), function(data) {
                $("#return").html(data);
            });
        }
    });
});
    
