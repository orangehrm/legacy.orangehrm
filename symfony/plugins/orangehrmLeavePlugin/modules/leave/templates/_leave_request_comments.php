<!-- <div id="leave_request_comments">
    <table class="table">
        <tbody>
        <th><?php echo __('Date');?></th>
        <th><?php echo __('Time');?></th>
        <th><?php echo __('Author');?></th>
        <th><?php echo __('Comment');?></th>
<?php 
$odd = true;
                
foreach ($requestComments as $comment): 
    
    $commentDate = new DateTime($comment->getCreated());
    $date = set_datepicker_date_format($commentDate->format('Y-m-d'));
    $time = $commentDate->format('H:i');
    $author = $comment->getCreatedByName();
    $comments = $comment->getComments();    
    $odd = !$odd;
    $class = $odd ? 'odd' : 'even';
?>

<tr class="<?php echo $class;?>">
    <td><?php echo $date;?></td>
    <td><?php echo $time;?></td>
    <td><?php echo $author;?></td>
    <td><?php echo $comments;?></td>
</tr>

        
<?php endforeach; ?>
        </tbody>
    </table>
</div> -->

<a href="#" id="view_request_comments"><?php echo __("View Leave Request Comments");?></a>
