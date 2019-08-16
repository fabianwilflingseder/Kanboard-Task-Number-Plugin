<div class="page-header">
    <h2>Task Numbers</h2>
</div>
<form method="post" action="<?= $this->url->href('TaskNumberController', 'save', array('plugin' => 'TaskNumberPlugin')) ?>">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('project_id', $values) ?>

    <fieldset>
        <legend><?= t('Settings') ?></legend>

        <?= $this->form->checkbox('ticketsenabled', 'Automatische Tasknummern verwenden', true, $values['ticketsenabled']) ?>
        <p class="form-help">Wenn diese Einstellung aktiviert wurde sollte sie nicht mehr deaktiviert werden!</p>

        <?= $this->form->label(t('Schema der Nummerierung (z.B. API)'), 'schema') ?>
        <?= $this->form->text('schema', $values, $errors, array('required')) ?>

        <?= $this->form->label(t('Aktuelle Nummer'), 'number') ?>
        <?= $this->form->text('number', $values, $errors, array('form-numeric')) ?>
    </fieldset>

    <?= $this->modal->submitButtons() ?>
</form>
