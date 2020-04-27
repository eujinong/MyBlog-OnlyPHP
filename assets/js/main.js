$("document").ready(function () {
    $('#login').modal({
        dismissible: true,
        opacity: .5,
        inDuration: 300,
        outDuration: 200,
        startingTop: '4%',
        endingTop: '10%',
    });

    $('select').material_select();

    $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 15,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        closeOnSelect: false
    });

    $('.blog_block .upvote button').each(function () {
        $(this).on('click', function() {
           var userId = $(this).attr('data-userId');
           var blogId = $(this).attr('data-blogId');
           var baseUrl = $(this).attr('data-baseurl');
           var activateElem = $(this).closest('div.like');
           blogLike($(this).next(),activateElem ,userId,blogId, baseUrl);
        });
    });


    $('.single_answer_content .upvote button').each(function () {
        $(this).on('click', function() {
           var userId = $(this).attr('data-userId');
           var blogId = $(this).attr('data-blogId');
           var baseUrl = $(this).attr('data-baseurl');
           var commentId = $(this).attr('data-commentId');
           var activateElem = $(this).closest('li.like');
           commentLike($(this).next(),activateElem ,userId,blogId, commentId, baseUrl);
        });
    });

    $('.reply .upvote button').each(function () {
        $(this).on('click', function() {
            var userId = $(this).attr('data-userId');
            var blogId = $(this).attr('data-blogId');
            var baseUrl = $(this).attr('data-baseurl');
            var commentId = $(this).attr('data-commentId');
            var replyId = $(this).attr('data-replyId');
            var activateElem = $(this).closest('li.like');
            replyLike($(this).next(),activateElem ,userId, blogId, commentId, replyId, baseUrl);
        });
    });


});

/**
 * called when user likes a blog
 * @param element
 * @param activateElem
 * @param userId
 * @param blogId
 * @param baseurl
 */
function blogLike(element, activateElem, userId, blogId, baseurl) {
    $.ajax({
        type: "POST",
        url: baseurl + "like/blog/" + blogId,
        headers: {
            'Authorization': 123
        },
        data: {
            "userId" : userId
        },
        dataType: "json",
        success: function(result){
            element.text(result.likeCount);
            if (result.liked) {
                if (!activateElem.hasClass('liked')) {
                    activateElem.addClass('liked')
                }
            } else {
                if (activateElem.hasClass('liked')) {
                    activateElem.removeClass('liked')
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(baseurl);
            alert(xhr.status);
            alert(thrownError);
        }

    });
}

/**
 * called when user likes a comment
 * @param element
 * @param activateElem
 * @param userId
 * @param blogId
 * @param commentId
 */
function commentLike(element, activateElem, userId, blogId, commentId, baseurl) {
    $.ajax({
        type: "POST",
        url: baseurl + "like/comment/"+commentId,
        headers: {
            'Authorization': 123
        },
        data: {
            "userId" : userId,
            "blogId": blogId
        },
        dataType: "json",
        success: function(result){
            element.text(result.likeCount);
            if (result.liked) {
                if (!activateElem.hasClass('liked')) {
                    activateElem.addClass('liked')
                }
            } else {
                if (activateElem.hasClass('liked')) {
                    activateElem.removeClass('liked')
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }

    });
}

/**
 * called when user like comment under comment
 * @param element
 * @param activateElem
 * @param userId
 * @param blogId
 * @param commentId
 * @param replyId
 */
function replyLike(element, activateElem, userId, blogId, commentId, replyId, baseurl) {
    $.ajax({
        type: "POST",
        url: baseurl + "like/reply/" + replyId,
        headers: {
            'Authorization': 123
        },
        data: {
            "userId" : userId,
            "blogId": blogId,
            "commentId": commentId
        },
        dataType: "json",
        success: function(result){
            element.text(result.likeCount);
            if (result.liked) {
                if (!activateElem.hasClass('liked')) {
                    activateElem.addClass('liked')
                }
            } else {
                if (activateElem.hasClass('liked')) {
                    activateElem.removeClass('liked')
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }

    });
}


function updateValue(element, value) {
    element.text(value);
}