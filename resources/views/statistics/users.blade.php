<x-app-layout>
  <x-section-header title="Statistisques des utilisateurs">
  </x-section-header>
  <div class="bg-white px-6 py-4 rounded-md shadow-lg">
      <div id="mon-chart" style="height: 500px; width: 800px;"></div>
  </div>
  @section('js')
      <script>
          google.charts.load('current', {
              'packages': ['corechart']
          });
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {

              var data = google.visualization.arrayToDataTable([
                  ['Catégories', 'Produits'],
                  @foreach ($data as $item) // On parcourt les catégories
                      [ "{{ $item->name }}", {{ $item->count() }} ], // Proportion des produits de la catégorie
                  @endforeach
              ]);

              var options = {
                  title: 'Statistique des commandes des differents plats de la cantine', // Le titre
                  is3D: true // En 3D
              };

              // On crée le chart en indiquant l'élément où le placer "#mon-chart"
              var chart = new google.visualization.PieChart(document.getElementById('mon-chart'));

              // On désine le chart avec les données et les options
              chart.draw(data, options);
          }
      </script>
  @endsection
</x-app-layout>
