<?php
interface BUSInterface
{
    /**
     * Returns a list of all models.
     *
     * @return array a list of all models
     */
    public function getAllModels();

    /**
     * Refreshes the data in the model list.
     */
    public function refreshData(): void;

    /**
     * Returns the model with the given id.
     *
     * @param int $id the id of the model to retrieve
     * @return mixed the model with the given id, or null if not found
     */
    public function getModelById(int $id);

    /**
     * Adds the given model to the database.
     *
     * @param mixed $model the model to add
     */
    public function addModel($model);

    /**
     * Updates the given model in the database.
     *
     * @param mixed $model the model to update
     */
    public function updateModel($model);

    /**
     * Deletes the model with the given id from the database.
     *
     * @param int $id the id of the model to delete
     */
    public function deleteModel(int $id);

    /**
     * Searches for models that match the given value in the specified columns.
     *
     * @param string $value the value to search for
     * @param array $columns the columns to search in
     */
    public function searchModel(string $value, array $columns);
}
