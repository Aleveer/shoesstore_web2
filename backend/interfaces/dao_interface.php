<?php
namespace backend\interfaces;

interface DAOInterface
{
    /**
     * Reads all entries from the database table associated with this DAO.
     *
     * @return array an array of all Entity objects in the table
     */
    public function readDatabase(): array;

    /**
     * Inserts an entity to the database table associated with this DAO.
     *
     * @param mixed $e the entity to insert
     * @return int the number of rows affected by the insert statement
     */
    public function insert($e): int;

    /**
     * Updates an existing entry in the database table associated with this DAO.
     *
     * @param mixed $e the entity to update
     * @return int the number of rows affected by the update statement
     */
    public function update($e): int;

    /**
     * Deletes a given entity from the database table associated with this DAO.
     *
     * @param int $id the primary key of the entry to delete
     * @return int the number of rows affected by the deletion statement
     */
    public function delete(int $id): int;

    /**
     * Searches the database table associated with this DAO for entities that match
     * the given condition in the specified columns.
     *
     * @param string $condition the search condition to use
     * @param array $columnNames the names of the columns to search in
     * @return array a list of entities that match the search condition
     */
    public function search(string $condition, array $columnNames): array;
}
