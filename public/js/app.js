
function populateForm(json)
{
    $.each(json, function(name, val){
        var $el = $('[name="'+name+'"]'),
            type = $el.attr('type');

        switch(type){
            case 'checkbox':
                $el.attr('checked', 'checked');
                break;
            case 'radio':
                $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                break;
            default:
                $el.val(val);
        }
    });

}

function toggleElement(id)
{
    $('#' + id).toggle('fast');
}

function showElement(id) {
    $('#' + id).show('fast');
}

function hideElement(id)
{
    $('#' + id).hide('fast');
}

function hideByClass(className)
{
    $('.' + className).hide('fast');
}

function removeByClass(className)
{
    $('.' + className).remove();
}

function handleReply(data)
{
    console.log(data);

    if (data.error) {
        return processError(data);
    } else {
        return processSuccess(data);
    }
}

function processError(data)
{
    var tempId = createElement(data.message, 'error', 'successDiv');

    setTimeout(function(){
        removeByClass('error');
    }, 6000);

    return tempId;
}

function processSuccess(data)
{
    var tempId = createElement(data.message, 'success', 'successDiv');

    setTimeout(function(){
        removeByClass('success');
    }, 3000);

    return tempId;
}


function createElement(content, className, id, type)
{
    if (! type) type = 'div';

    var innerDiv = document.createElement('div');

    if (id) {
        var useId = id;
    } else {
        var useId = idGenerator();
    }

    innerDiv.id = useId;

    if (className)
        innerDiv.className = className;

    innerDiv.innerHTML = content;

    document.getElementsByTagName('body')[0].appendChild(innerDiv);

    return useId;
}


function idGenerator() {
    var S4 = function() {
        return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return S4() + S4() + S4();
}
