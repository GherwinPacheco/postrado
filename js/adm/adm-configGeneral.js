function removeTerm(elem){
    $(elem).closest('.termItem').remove();
}

function addTerms(title, content){
    var termTitle = title !== undefined ? title : '';
    var termContent = content !== undefined ? content : '';

    $("#termsList").append(`
        <li class="termItem mb-4">
            <div class="row">
                <div class="col col-10">
                    <input type="text" class="form-control text-medium w-75 mb-2" name="termTitle[]" value="${termTitle}" placeholder="Term Title" required>
                    <textarea class="form-control" rows="5" name="termContent[]" required>${termContent}</textarea>
                </div>
                <div class="col col-2 d-flex align-items-center">
                    <button class="btn btn-outline-danger" onclick="removeTerm(this)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </li>    
    `);
}

$("input").on("input", function(){
    $("#save-btn").removeClass("hide");
})

$("#submitBtn").click(function(){
    $("#config-form").submit();
});


$(document).ready(function(){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'terms_data'
        },
        success: function(data){
            for(term of data){
                addTerms(term.title, term.content);
            }
        }
    });
});