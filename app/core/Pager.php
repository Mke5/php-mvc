<?php 

/**
 * Pagination class for handling pagination
 */

namespace Core;

defined('ROOTPATH') OR exit('Access Denied!');

class Pager
{
    public $links = [];
    public $offset = 0;
    public $page_number = 1;
    public $start = 1;
    public $end = 1;
    public $limit = 10;
    public $nav_class = "";
    public $ul_class = "pagination justify-content-center";
    public $li_class = "page-item";
    public $a_class = "page-link";

    public function __construct($limit = 10, $extras = 1)
    {
        $this->limit = $limit;
        $this->page_number = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $this->page_number = max(1, $this->page_number); // Ensure at least 1

        $this->start = max(1, $this->page_number - $extras);
        $this->end = $this->page_number + $extras;
        $this->offset = ($this->page_number - 1) * $this->limit;

        $this->generateLinks();
    }

    private function generateLinks()
    {
        $url = $_GET['url'] ?? '';

        // Preserve query parameters while changing `page`
        $query_params = $_GET;
        unset($query_params['url']);

        // Generate first page link
        $query_params['page'] = 1;
        $this->links['first'] = ROOT . "/" . $url . "?" . http_build_query($query_params);

        // Generate current page link
        $query_params['page'] = $this->page_number;
        $this->links['current'] = ROOT . "/" . $url . "?" . http_build_query($query_params);

        // Generate next page link
        $query_params['page'] = $this->page_number + 1;
        $this->links['next'] = ROOT . "/" . $url . "?" . http_build_query($query_params);
    }

    public function display($record_count = null)
    {
        $record_count = $record_count ?? $this->limit;

        if ($record_count == $this->limit || $this->page_number > 1) {
            ?>
            <br class="clearfix">
            <div>
                <nav class="<?= $this->nav_class ?>">
                    <ul class="<?= $this->ul_class ?>">
                        <?php if ($this->page_number > 1): ?>
                            <li class="<?= $this->li_class ?>">
                                <a class="<?= $this->a_class ?>" href="<?= $this->links['first'] ?>">First</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($x = $this->start; $x <= $this->end; $x++): ?>
                            <li class="<?= $this->li_class ?> <?= ($x == $this->page_number) ? ' active ' : '' ?>">
                                <a class="<?= $this->a_class ?>" href="
                                    <?= preg_replace('/page=\d+/', "page=" . $x, $this->links['current']) ?>
                                "><?= $x ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($record_count == $this->limit): ?>
                            <li class="<?= $this->li_class ?>">
                                <a class="<?= $this->a_class ?>" href="<?= $this->links['next'] ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php
        }
    }
}
