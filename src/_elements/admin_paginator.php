<div class="lva8">
    <a href="" class="change first">first</a>
    <a href="" class="change previous">previous</a>

    page <input type="text" name="p" style="width:30px;text-align:center;"/> / <?php echo $struct["nofPages"]?>
    <input type="button" value="go" />

    <a href="" class="change next">next</a>
    <a href="" class="change last">last</a>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        if(typeof lva8 === 'undefined') {
            lva8 = $('.lva8');
            p = <?php echo $struct["pageNo"]?>;
            field4p = $('.filter').find('input[name=p]');
            field4p.val(p);
            lva8.find('input[name=p]').val(p);
            lva8.find('.change').click(function(e) {
                e.preventDefault();
                if($(this).hasClass('first'))
                    field4p.val(1);
                if($(this).hasClass('previous') && p > 1)
                    field4p.val(p-1);
                if($(this).hasClass('next') && p < <?php echo $struct["nofPages"]?>)
                    field4p.val(p+1);
                if($(this).hasClass('last'))
                    field4p.val(<?php echo $struct["nofPages"]?>);
                $('.filter').submit();
            });
            <?php if($this->data["_g"]["p"]!=$struct["pageNo"]): ?>
            $('.filter').submit();
            <?php endif; ?>
        }
    });
</script>