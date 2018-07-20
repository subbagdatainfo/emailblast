<div class="wrap">
        
    
   <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
   <h2>
      <?php _e("My Lists","sola") ?>
       <a href="<?php echo wp_nonce_url('?page=sola-nl-menu&action=new_list', 'sola_nl_new_list'); ?>" class="add-new-h2"><?php _e("Add List","sola") ?></a>
   </h2>
   <table class="wp-list-table widefat fixed">
      <thead>
         <tr>
            <th><?php _e("List Name","sola"); ?></th>
            <th><?php _e("Description","sola"); ?></th>
            <th><?php _e("Total Subscribers","sola"); ?></th>
         </tr>
      </thead>
      <tbody>
         <?php
            $lists = sola_nl_get_lists();
            foreach($lists as $list){?>
               <tr>
                  <td>
                     <strong>
                        <a href="<?php echo wp_nonce_url('?page=sola-nl-menu&action=new_list&list_id=' . $list->list_id, 'sola_nl_new_list'); ?>">
                           <?php echo $list->list_name; ?>
                        </a>
                     </strong>
                     <div class="row-actions">
                        <span>
                           
                              <a href="<?php echo wp_nonce_url('?page=sola-nl-menu&action=new_list&list_id=' . $list->list_id, 'sola_nl_new_list'); ?>">
                                 <?php _e("Edit","sola");?>
                             </a>
                        </span> | 
                        <span>
                           <a href="?page=sola-nl-menu-subscribers&list_id=<?php echo $list->list_id ?>">
                              <?php _e("View Subscribers","sola"); ?>
                           </a>
                        </span> | 
                        <span class="trash">
                           <a href="?page=sola-nl-menu-lists&action=delete_list&list_id=<?php echo $list->list_id ?>">
                              <?php _e("Delete","sola"); ?>
                           </a>
                        </span> |
                        <span>                        
                           <a href="?page=sola-nl-menu-subscribers&action=sola_csv_export&list_id=<?php echo $list->list_id ?>">
                              <?php _e("Export List","sola"); ?>
                           </a>
                        </span> |
                     </div>
                  </td>
                  <td>
                     <?php echo $list->list_description; ?>
                  </td>
                  <td>
                     <?php $list_id = $list->list_id;
                        echo sola_nl_total_list_subscribers($list_id); 
                     ?>
                  </td>
               </tr>
               <?php
            }
         ?>
      </tbody>
   </table>
</div>
<?php include 'footer.php'; ?>