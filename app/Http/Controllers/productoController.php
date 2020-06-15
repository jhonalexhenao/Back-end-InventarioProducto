<?php

namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Producto;

class productoController extends Controller
{
    /*
    public function index() {

        $json = array(
            "detalle"=>"no encontradooooo"
        );
        return json_encode($json, true); 
    }
    */


    /**
     * @return void
     * Método index
     */
    public function index() {

        $producto = Producto::all();
        $json =  array();

        if(!empty($producto)){

            $json = array(
                "status"=>"200",
                "total de Productos"=>count($producto),
                "detalle"=>$producto
            );
    
        }else{

            $json = array(

                "detalle" =>"No tiene producto para mostrar"

            );
        }

        return json_encode($json, true);
    
    }

    /**
     * @param Request $request
     * Crear Registro
     * @return void
     */
    public function store(Request $request) {
        //echo '<pre>'; print_r($request); echo '</pre>';       
        //recoger datos 
        $datos = array("nombreProducto"=>$request->input("nombreProducto"),
                        "referencia"=>$request->input("referencia"),
                        "precio"=>$request->input("precio"),
                        "peso"=>$request->input("peso"),
                        "categoria"=>$request->input("categoria"),
                        "stock"=>$request->input("stock"), );

        if(!empty($datos)){

           // echo '<pre>'; print_r($datos); echo '</pre>';       
            echo '<pre>'; print_r($request->input("nombreProducto")); echo '</pre>';       

        //Validar datos 
        $validator = Validator::make($datos, [

            'nombreProducto' => 'required|max:255',
            'referencia' => 'required',
            'precio' => 'required',
            'peso' => 'required',
            'categoria' => 'required',            
            'stock' => 'required'
            
        ]); 

        //si falla la validación
        
        if ($validator->fails()) {
            
            $json = array(

                "detalle" =>"Registro con errores: no se permite caracteres especiales"

            );

            return json_encode($json, true);

        }else{

            
            $producto = new Producto();
            $producto->nombreProducto = $datos["nombreProducto"];
            $producto->referencia = $datos["referencia"];
            $producto->precio = $datos["precio"];
            $producto->peso = $datos["peso"];
            $producto->categoria = $datos["categoria"];
            $producto->stock = $datos["stock"];


            $producto->save();

            $json = array(

                "status" => 200,
                "detalle" => "Registro exitoso", 
 
            );
            
            return json_encode($json, true);

            }

        }else {

            $json = array(

                "status" => 404,
                "detalle" =>"Registro con errores"

            );

            return json_encode($json, true);
        }
        //echo '<pre>'; print_r($datos); echo '</pre>';
    }

    /**
     * @param mixed $id
     * @param Request $request
     * Actualiza producto
     * @return void
     */
    public function update($id, Request $request) {

        $json = array();

        //recoger datos 
        $datos = array("nombreProducto"=>$request->input("nombreProducto"),
                        "referencia"=>$request->input("referencia"),
                        "precio"=>$request->input("precio"),
                        "peso"=>$request->input("peso"),
                        "categoria"=>$request->input("categoria"),
                        "stock"=>$request->input("stock"), 
                        "venta"=>$request->input("venta"), );

        if(!empty($datos)){

                       

        //Validar datos 
        $validator = Validator::make($datos, [

            'nombreProducto' => 'required|max:255',
            'referencia' => 'required',
            'precio' => 'required',
            'peso' => 'required',
            'categoria' => 'required',            
            'stock' => 'required'
            
        ]); 

        //si falla la validación
        
        if ($validator->fails()) {
            
            $json = array(

                "detalle" =>"Registro con errores aaa"

            );

            return json_encode($json, true);

        }else{

            $traer_producto = Producto::where("id", $id)->get(); 
            
            if(!empty($traer_producto[0]["id"])){
               
                if($id == $traer_producto[0]["id"]){

                   

                    if($datos["stock"]< 0){

                        $json = array(
    
                            "status" => 404,
                            "detalle" =>"El valor ingresado debe ser igual o mayor a cero"
            
                        );
            
                        return json_encode($json, true);

                    }elseif (!empty($datos["venta"])){

                        $traer_producto = Producto::where("id", $id)->get();

                        $stockProducto = $traer_producto[0]["stock"] - $datos["venta"];
                        //echo '<pre>'; print_r($stockProducto); echo '</pre>';

                        if($stockProducto > 0){

                            $datos = array("nombreProducto"=>$datos["nombreProducto"],
                            "referencia"=>$datos["referencia"],
                            "precio"=>$datos["precio"],
                            "categoria"=>$datos["categoria"],
                            "peso"=>$datos["peso"],                                
                            "stock"=>$stockProducto, );
                
                            $producto = Producto::where("id", $id)->update($datos);
    
                                $json = array("status" => 200,
                                             "detalle" => "Se ha realizado la venta exitoso, su producto ha sido actualizado",
                                              "Stock"=>$stockProducto );

                                return json_encode($json, true);

                        }else {

                            $json = array(
    
                                "status" => 200,
                                "detalle" => "No se realiza venta por no tener productos en stock"
                 
                            );
            
                            return json_encode($json, true);
                            
                        }

                        
                    }

                    $datos = array("nombreProducto"=>$datos["nombreProducto"],
                                    "referencia"=>$datos["referencia"],
                                    "precio"=>$datos["precio"],
                                    "categoria"=>$datos["categoria"],
                                    "peso"=>$datos["peso"],                                
                                    "stock"=>$datos["stock"], );
                                                     
                    $producto = Producto::where("id", $id)->update($datos);
    
                    $json = array(
    
                        "status" => 200,
                        "detalle" => "Registro exitoso, su producto ha sido actualizado", 
         
                    );
    
                    return json_encode($json, true);
    
                }else{
    
                    $json = array(
    
                        "status" => 404,
                        "detalle" =>"No fue posible modificar el producto"
        
                    );
        
                    return json_encode($json, true);
    
    
                }
            }else{
                $json = array(

                    "status" => 200,
                    "detalle" =>"No hay ningún producto registrado"
    
                );
    
                return json_encode($json, true);
            }

            


            }

        }else {

            $json = array(

                "status" => 404,
                "detalle" =>"Registro con errores"

            );

            return json_encode($json, true);
        }
        //echo '<pre>'; print_r($datos); echo '</pre>';
    }

    public function destroy($id, Request $request) {

        $producto = Producto::all();
        $json = array();

        $validar = Producto::where("id", $id)->get(); 
            
            if(!empty($validar[0]["id"])){

                $producto = Producto::where("id", $id)->delete();

                $json = array(

                    "status" => 200,
                    "detalle" =>"Se ha borrado su producto con éxito"
    
                );
    
                return json_encode($json, true);

            }else{

                $json = array(

                    "status" => 200,
                    "detalle" =>"El producto no Existe"
    
                );
    
                return json_encode($json, true);
            }
    
    }

}
