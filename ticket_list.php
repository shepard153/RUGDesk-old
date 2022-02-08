<?php
include("includes/staff_template.php");

$count = 0;
if (isset($_GET['filter'])){$filter = $_GET['filter'];}
?>

 <div class="container-fluid" style="background:#F2F2F2; overflow: auto;">
    <div class="row">
      <div class="col ">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Zgłoszenia</p>
        <a href="ticket_list.php?filter=0" class="btn btn-success">Nowe</a>
        <a href="ticket_list.php?filter=1" class="btn btn-warning">Aktywne</a>
        <a href="ticket_list.php?filter=2" class="btn btn-danger">Zamknięte</a>
        <div class="col rounded shadow" style="background: white; margin-top: 1vw;">
            <table class="table table-striped table-hover">

                <?php
                    try
                    {
                        $dzial = $_SESSION['dzial'];
                        
                        if (isset($filter) && $dzial != 'All')
                        {
                            $total = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial' AND status = '$filter'")->fetchColumn();
                        }
                        elseif (isset($filter) && $dzial == 'All')
                        {
                            $total = $conn->query("SELECT COUNT(*) FROM TicketList WHERE status = '$filter'")->fetchColumn();
                        }
                        elseif (!isset($filter) && $dzial != 'All')
                        {
                            $total = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial'")->fetchColumn();                           
                        }
                        else
                        {
                            $total = $conn->query("SELECT COUNT(*) FROM TicketList WHERE status = 0 OR status = 1")->fetchColumn();
                        }
                                
                        if($total != 0)
                        {
                            $limit = 20;
                                
                            $pages = ceil($total / $limit);
                                
                            $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                                'options' => array(
                                    'default'   => 1,
                                    'min_range' => 1,
                                ),
                            )));
                                
                            $offset = (int)($page - 1)  * $limit;
                                
                            $start = $offset + 1;
                            $end = min(($offset + $limit), $total);

                            $columns = array('id','nazwa','dzial','problem','linia','stanowisko','priorytet','data_zgloszenia','data_podjecia', 'data_zamkniecia', 'status');
                            $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[7];
                            $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC' : 'DESC';

                            $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
                                                        
                            $up_or_down = str_replace(array('ASC','DESC'), array('#arrow-up','#arrow-down'), $sort_order);
                            
                            if (isset($filter) && $dzial == 'All')
                            {
                                $stmt = $conn->prepare("SELECT * FROM TicketList WHERE status=:status ORDER BY $column $sort_order OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY");  
                                $stmt->bindParam(':status',$filter);
                            }
                            elseif (isset($filter) && $dzial != 'All')
                            {
                                $stmt = $conn->prepare("SELECT * FROM TicketList WHERE status=:status AND dzial=:dzial ORDER BY $column $sort_order OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY");  
                                $stmt->bindParam(':status',$filter);
                                $stmt->bindParam('dzial', $dzial);
                            }
                            else
                            {
                                if ($dzial == 'All')
                                {
                                    $stmt = $conn->prepare("SELECT * FROM TicketList WHERE status = 0 OR status = 1 ORDER BY $column $sort_order OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY");                          
                                }
                                else
                                {
                                    $stmt = $conn->prepare("SELECT * FROM TicketList WHERE dzial = :dzial AND (status = 0 OR status = 1) ORDER BY $column $sort_order OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY");
                                    $stmt->bindParam('dzial', $dzial);
                                }
                            }

                            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);                        
                            $stmt->execute();
                            
                            $count = $stmt->rowCount();

                            function checkFilter(){
                                if (isset($_GET['filter'])){
                                    echo '&filter='.$_GET['filter'];
                                }
                            }
                                
                            if ($count < 0) {
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $iterator = new IteratorIterator($stmt);

                                echo '<div id="paging"><p class="lead" style="padding: 0.7vw 0px 0px 1vw;"> Wyświetlane ', $start, '-', $end, ' z ', $total, ' wyników </p></div>';?>

                                    <thead>               
                                        <tr>
                                            <td><b><a href="ticket_list.php?column=ticketID&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="#arrows-expand"></use></svg></a>ID</b</td>
                                            <td><b><a href="ticket_list.php?column=nazwa&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'nazwa' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Nazwa</b</td>
                                            <td><b><a href="ticket_list.php?column=dzial&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'dzial' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Dział</b></td>
                                            <td><b><a href="ticket_list.php?column=problem&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'problem' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Problem</b></td>
                                            <td><b><a href="ticket_list.php?column=linia&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'linia' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Obszar</b></td>
                                            <td><b><a href="ticket_list.php?column=stanowisko&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'stanowisko' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Stanowisko</b></td>
                                            <td><b><a href="ticket_list.php?column=priorytet&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'priorytet' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Priorytet</b></td>
                                            <td><b><a href="ticket_list.php?column=data_zgloszenia&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'data_zgloszenia' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Data zgłoszenia</b></td>
                                        <?php
                                            if (isset($filter) && $filter == '2')
                                            {
                                        ?>
                                               <td><b><a href="ticket_list.php?column=data_zamkniecia&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'data_zamkniecia' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Data zamknięcia</b></td>
                                        <?php
                                            }
                                            else
                                            {
                                        ?>
                                                <td><b><a href="ticket_list.php?column=data_podjecia&order=<?php echo $asc_or_desc; checkFilter(); ?>"><svg class="bi me-2" width="16" height="16"><use href="<?php echo $column == 'data_podjecia' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Data podjęcia</b></td>
                                        <?php
                                            }
                                        ?>
                                            <td><b><a href="ticket_list.php?column=status&order=<?php echo $asc_or_desc; ?>"><svg class="bi me-2" width="16" height="16"><use xlink:href="<?php echo $column == 'status' ? $up_or_down : '#arrows-expand'; ?>"></use></svg></a>Status</b></td>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                foreach ($iterator as $row){
                                    ?>

                                    <tr class='clickable-row' data-href='ticket_details.php?ticketID=<?php echo $row['ticketID']; ?>' <?php if($row['priorytet'] == "4"){echo 'style="background-color: #ff7f7f"';} ?>>
                                    <td><strong><a href="ticket_details.php?ticketID=<?php echo $row["ticketID"]; ?>" class="link-success text-decoration-none"><?php echo $row["ticketID"];?></a></strong></td>
                                    <td><?php echo $row["nazwa"]; ?></td>
                                    <td><?php echo $row["dzial"]; ?></td>
                                    <td style="width: 20%"><strong><a href="ticket_details.php?ticketID=<?php echo $row["ticketID"]; ?>" class="link-success text-decoration-none"><?php echo  $row["problem"]; ?></a></strong></td>
                                    <td><?php echo $row["linia"]; ?></td>
                                    <td><?php echo $row["stanowisko"]; ?></td>
                                    <td>
                                    <?php
                                        switch ($row["priorytet"]){
                                            case "0":
                                                echo "Powiadomienie";
                                                break;
                                            case "1":
                                                echo "Niski";
                                                break;
                                            case "2":
                                                echo "Średni";
                                                break;
                                            case "3":
                                                echo "Wysoki";
                                                break;
                                            case "4":
                                                echo "Krytyczny";
                                                break;
                                            default: echo "----------";
                                        }
                                    ?>
                                    </td>
                                    <td><?php echo $row["data_zgloszenia"]; ?></td>
                                    <?php
                                    if (isset($filter) && $filter == '2')
                                    {
                                        echo '<td>', $row["data_zamkniecia"],'</td>';
                                    }
                                    else
                                    {
                                        echo '<td>', $row["data_podjecia"],'</td>';
                                    }
                                    echo '<td>';
                                    if ($row["status"] == '0') {echo "<span class='badge rounded-pill bg-success'>Nowe</span>";}
                                    elseif ($row["status"] == '1') {echo "<span class='badge rounded-pill bg-warning'>Aktywne</span>";}
                                    elseif ($row["status"] == '2') {echo "<span class='badge rounded-pill bg-danger'>Zamknięte</span>";}
                                    echo '</td>';
                                    echo '</tr>';
                                }

                            }
                            else{
                                echo '<p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>';
                            }
                        }
                        else{
                            echo '<p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>';
                        }
                    } 
                    catch (Exception $e){
                        echo '<p>', $e->getMessage(), '</p>';
                    }                           
                ?>

                </table>
                <?php
                if (isset($filter) && $count < 0){
                    echo '<nav aria-label="Strony" style="padding: 0vw 0vw 0vw 1vw;">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="?filter='.$filter.'&page=',($page - 1),'" tabindex="-1">&laquo;</a>
                                </li>
                                <li>
                                    <form class="pageNumberForm" type="GET">
                                        <input type="number" name="page" id="pageNumber" class="form-control" min="1" max="',$pages,'" value="',$page,'"/>
                                    </form>
                                </li>
                                <li class="page-item">
                                    <p class="lead" style="padding: 0.2vw 0.4vw 0.2vw 0.4vw;"> z ',$pages,'</p>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?filter='.$filter.'&page=',($page + 1),'">&raquo;</a>
                                </li>
                            </ul>
                        </nav>​';
                }
                elseif ($count < 0){
                    echo '<nav aria-label="Strony" style="padding: 0vw 0vw 0vw 1vw;">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="?page=',($page - 1),'" tabindex="-1">&laquo;</a>
                                </li>
                                <li>
                                    <form class="pageNumberForm" type="GET">
                                        <input type="number" name="page" id="pageNumber" class="form-control" min="1" max="',$pages,'" value="',$page,'"/>
                                    </form>
                                </li>
                                <li class="page-item">
                                    <p class="lead" style="padding: 0.2vw 0.4vw 0.2vw 0.4vw;"> z ',$pages,'</p>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=',($page + 1),'">&raquo;</a>
                                </li>
                            </ul>
                        </nav>​';
                }
                else{} ?>

            </div>
        </div>
        
        <script type="text/javascript">
        $('.pageNumberForm').submit(function() {
            var pageInput = $(this).children('#pageNumber').first();
            var pageInputValue = pageInput.val();

            if (pageInputValue < pageInput.attr('min') || pageInputValue > pageInput.attr('max') || !pageInputValue.match(/\d+/)) {
                alert('Podaj wartość pomiędzy ' + pageInput.attr('min') + ' a ' + pageInput.attr('max'));
                return false;
            }
        });
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
        </script>
        <?php include('includes/staff_footer.php'); ?>