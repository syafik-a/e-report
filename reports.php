<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/utils/functions.php");
$reports = query("SELECT reports.*, users.name as user_name FROM reports 
                    JOIN users ON reports.user_id = users.id ORDER BY created_at ASC");
header('refresh:3;Content-Type: text/html; charset=UTF-8');

?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
    <!-- Main Content Cards (Left Side) -->
    <div class="col-span-3 grid grid-cols-1 gap-4">
        <!-- Card 1 -->
        <?php
        if (empty($reports)) : ?>
            <h1 class="font-bold text-3xl text-black">Data Kosong</h1>
        <?php else : ?>
            <?php
            foreach ($reports as $report) :
                $report['content'] = html_entity_decode(html_entity_decode(strval($report['content'])));

            ?>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-fit mb-4">
                    <a href="index.php?page=detail&id=<?= $report['id'] ?>" class="block h-full">
                        <div class="max-w-full border border-gray-200 rounded-lg shadow h-full flex flex-col">
                            <?php if ($report['thumbnail']): ?>
                                <img class="rounded-t-lg w-full h-48 object-cover" alt="<?= $report['thumbnail'] ?>" src="<?= "../assets/upload/" . $report['thumbnail'] ?>" />
                            <?php endif ?>
                            <div class="p-5 flex-grow flex flex-col">
                                <div class="flex items-center mb-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-600 truncate"><?= $report['user_name'] ?></p>
                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400"><?= $report['created_at'] ?></p>
                                    </div>
                                </div>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-600"><?= $report['title'] ?></h5>
                                <?= $report['content'] ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif ?>

    </div>
</div>