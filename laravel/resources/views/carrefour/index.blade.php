<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Scraper Carrefour</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-auto ms-auto me-auto">
                <form action="{{route('carrefour.search')}}" class="d-flex" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="search" class="form-control me-2"/>
                    </div>
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <a href="{{route('carrefour.despensa')}}" class="btn btn-info btn-sm">La Despensa</a>
                <a href="{{route('carrefour.frescos')}}" class="btn btn-info btn-sm">Productos Frescos</a>
                <a href="{{route('carrefour.bebidas')}}" class="btn btn-info btn-sm">Bebidas</a>
                <a href="{{route('carrefour.perfumeria_e_higiene')}}" class="btn btn-info btn-sm">Perfumería e Higiene</a>
                <a href="{{route('carrefour.limpieza_y_hogar')}}" class="btn btn-info btn-sm">Limpieza y Hogar</a>
                <a href="{{route('carrefour.bebe')}}" class="btn btn-info btn-sm">Bebé</a>
                <a href="{{route('carrefour.mascotas')}}" class="btn btn-info btn-sm">Mascotas</a>
                <a href="{{route('carrefour.parafarmacia')}}" class="btn btn-info btn-sm">Parafarmacia</a>
                <a href="{{route('carrefour.load')}}" class="btn btn-success btn-sm float-end">Cargar Productos</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-5">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-3 g-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="col-6">
                            <img src="<?php echo "$product->image"?>" class="img-fluid">
                        </div>
                        <div class="col-12">
                            <span>{{$product->price}} €</span>
                        </div>
                        <div class="col-12">
                            <span>{{$product->name}}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-12">
                            <a href="" class="btn btn-primary">Añadir</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
