'use strict';

const createRecordForm = document.querySelector('#gotoCreateRecord');
const editRecordForm = document.querySelector('#gotoEditRecord');
const deleteRecordForm = document.querySelector('#gotoDeleteRecord');
const duplicateRecordForm = document.querySelector('#gotoDuplicateRecord');

const createRecord = document.querySelector('#createRecord');
const editRecord = document.querySelector('#editRecord');
const deleteRecord = document.querySelector('#deleteRecord');
const duplicateRecord = document.querySelector('#duplicateRecord');

createRecord.addEventListener('click', function( ) {
    createRecordForm.submit();
});
editRecord.addEventListener('click', function( ) {
    editRecordForm.submit();
});
deleteRecord.addEventListener('click', function( ) {
    deleteRecordForm.submit();
});
duplicateRecord.addEventListener('click', function( ) {
    duplicateRecordForm.submit();
});
