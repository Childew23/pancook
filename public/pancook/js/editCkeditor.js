    document.addEventListener('DOMContentLoaded', () => {
      let editorInstance;

      // 1) Création de l'éditeur
      ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
          editorInstance = editor;

          // Initial sync (au cas où tu as un contenu déjà enregistré)
          document.getElementById('post_content').value = editor.getData();
        })
        .catch( error => console.error( error ) );

      // 2) Avant chaque envoi du form, on remet à jour le hidden
      document
        .getElementById('post-form')
        .addEventListener('submit', e => {
          if (editorInstance) {
            document.getElementById('post_content').value = editorInstance.getData();
          }
        });
    });