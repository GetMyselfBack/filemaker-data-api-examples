// invalid フィールドがある場合の処理
(( ) => {
  'use strict'

  // Bootstrap の Validation を使用するフォームを取得する
  const forms = document.querySelectorAll('.needs-validation')

  // フィールドをチェックして、submit を中止する
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})( )
