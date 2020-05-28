<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"C:\wamp64\www\1805\shop\tp5\public/../application/index\view\product\test.html";i:1539247827;}*/ ?>

<script type="text/javascript" src="__STATIC__/jquery-3.2.1.min.js"></script>
<form action="<?php echo url('product/testDo'); ?>">

<table>
    <tr>
        <td>添加班级</td>
        <td><button id="addClass" type="button">+</button></td>
    </tr>
</table>
<div></div>
    <hr/>
    <input type="submit"/>
</form>
<script type="text/javascript">
    var class_input = '<div class="class_a" len="0">班级名称：<input type="text" name="class[]"/>' +
            '<button type="button" class="student">+</button><br/></div>';
    $('#addClass').click(function(){
        var len = $('.class_a').length;
        $('table').next('div').append( class_input );
        $('.class_a').last().attr('len' , len);
    });

    var student_input = '<div style="margin-left: 30px">' +
            '学生名称：<input type="text" name="student[__LEN__][]"/>';

    $(document).on('click' , '.student',function(){
        var len = $(this).parents('.class_a').attr('len');
//        alert(len);
        student_input = student_input.replace( '__LEN__' , len);
        $(this).next().after(student_input);
        student_input = '<div style="margin-left: 30px">' +
                '学生名称：<input type="text" name="student[__LEN__][]"/>';
    });
</script>