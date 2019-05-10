<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
        <?php $classname = isset($errors['name']) ? 'form__input--error' : '';
          $value = $task['name'] ?? ''; ?>
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input <?= strip_tags($classname);?>" type="text" name="name" id="name"
            value="<?= strip_tags($value); ?>" placeholder="Введите название">
        <?php if (isset($errors['name'])) : ?>
        <p class='form__message'><?= strip_tags($errors['name'] ?? ''); ?></p>
        <?php endif ?>
    </div>

    <div class="form__row">
        <?php $classname = isset($errors['project']) ? 'form__input--error' : '';
        $value = $task['project'] ?? ''; ?>
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <select class="form__input form__input--select <?=strip_tags($classname); ?>" name="project" id="project">
            <?php foreach ($projects as $value) : ?>
            <option value="<?= $value['category'] ?>" <?php if ($task['project'] === $value['category']) : ?> selected
                <?php endif ?>><?= $value['category']; ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['project'])): ?>
        <p class="form__message"><?=strip_tags($errors['project'] ?? ''); ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <?php $classname = isset($errors['date']) ? 'form__input--error' : '';
        $value = $task['date'] ?? ''; ?>
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date <?= strip_tags($classname);?>" type="text" name="date" id="date"
            value="<?= strip_tags($task['date']); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        <?php if (isset($errors['date'])) : ?>
        <p class='form__message'><?= strip_tags($errors['date'] ?? ''); ?></p>
        <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="file" value="">

            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>