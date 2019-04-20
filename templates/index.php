<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post" autocomplete="off">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
            <?php if ($show_complete_tasks === 1): ?> checked<?php endif ;?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<?php foreach ($tasks as $val): ?>
<table class="tasks"><?php if(isset($val['done']) && (!$val['done'] || ($val['done'] && $show_complete_tasks))): ?>
    <tr class="tasks__item task 
    <?php if ($val['done']) { echo 'task--completed';}
    elseif (isDateImportant ($val['execution_date'])) {echo'task--important';} ?>">
        <td class=" task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1"
                    <?php if($val['done']): ?> checked <?php endif; ?>>
                <?php if (isset($val['purpose'])) : ?>
                <span class="checkbox__text"><?= strip_tags($val['purpose']); ?></span>
                <?php endif; ?>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#">Home.psd</a>
        </td>
        <td class="task__date">
            <?php if (isset($val['execution_date'])): ?>
            <?= strip_tags($val['execution_date']); ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
</table>
<?php endforeach; ?>