@if(session()->has('success'))
    <uikit-alert type="success">
        {{session()->get('success')}}
    </uikit-alert>
@endif
@if($errors->any())
    <uikit-alert type="danger">
      Veuillez corriger les erreurs et essayer de soumettre à nouveau.
    </uikit-alert>
@endif
