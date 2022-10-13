<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmail/Exception.php';
require 'phpmail/PHPMailer.php';
require 'phpmail/SMTP.php';

$mail = new PHPMailer(true);
class AuthController extends Controller
{
    // postman http://127.0.0.1:8000/api/login
    public function listar($id)
    {
        if ($id != 0) {
            $personas = DB::select(
                "SELECT * FROM empleos where estado=1 and visualizacion='$id' ORDER BY fechacreacion DESC "
            );
            if (count($personas) > 0) {
                $response = [
                    'data' => $personas,
                    'res' => true,
                ];
                return response($response, 200);
            } else {
                $response = [
                    'data' => $personas,
                    'res' => false,
                ];

                return response($response, 200);
            }
        } else {
            $personas = DB::select(
                'SELECT * FROM empleos where estado=1  ORDER BY fechacreacion DESC '
            );
            if (count($personas) > 0) {
                $response = [
                    'data' => $personas,
                    'res' => true,
                ];
                return response($response, 200);
            } else {
                $response = [
                    'data' => $personas,
                    'res' => false,
                ];

                return response($response, 200);
            }
        }
    }

    public function EmpleosActivos($id)
    {
        if ($id != 0) {
            $personas = DB::select(
                "SELECT E.idempleo as idempleo, EM.idempresa as idempresa ,E.nombre as empleo, EM.nombre as empresa,E.pefil descri FROM empleos as E inner join empresa as EM on E.idempresa=EM.idempresa  where E.estado=1 and E.visualizacion='$id' ORDER BY E.fechacreacion DESC "
            );
            if (count($personas) > 0) {
                $response = [
                    'data' => $personas,
                    'res' => true,
                ];
                return response($response, 200);
            } else {
                $response = [
                    'data' => $personas,
                    'res' => false,
                ];

                return response($response, 200);
            }
        } else {
            $personas = DB::select(
                'SELECT E.idempleo as idempleo, EM.idempresa as idempresa, E.nombre as empleo, EM.nombre as empresa,E.pefil descri FROM empleos as E inner join empresa as EM on E.idempresa=EM.idempresa  where E.estado=1  ORDER BY E.fechacreacion DESC'
            );
            if (count($personas) > 0) {
                $response = [
                    'data' => $personas,
                    'res' => true,
                ];
                return response($response, 200);
            } else {
                $response = [
                    'data' => $personas,
                    'res' => false,
                ];

                return response($response, 200);
            }
        }
    }

    public function VerEmpleos(Request $request)
    {
        $personas = DB::select('SELECT * FROM `empleos`  where estado=1 ');

        if (count($personas) > 0) {
            $dataenviof = [];
            $dataenviof['res'] = true;
            $dataenviof['data'] = $personas;

            return response($dataenviof, 200);
        } else {
            $dataenviof = [];
            $dataenviof['res'] = false;
            $dataenviof['data'] = null;
        }
    }

    public function RegistroEmpleo(Request $request)
    {
        $rules = [
            'idempresa' => 'required|integer',
            'nombre' => 'required|string|unique:empleos,nombre',
            'pefil' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'msj' => false,
                'msg' => 'ok',
                'detalle' => 'error',
                'error' => $validator->errors(),
            ];

            return response($response, 400);
        } else {
            $idempresa = $request['idempresa'];
            $nombre = $request['nombre'];
            $pefil = $request['pefil'];

            $users1 = DB::insert(
                'insert into empleos (idempresa, estado, nombre, idusuario, pefil, fechacreacion, visualizacion) values (?,?,?,?,?,?,?)',
                [$idempresa, '1', $nombre, '0', $pefil, now(), 0]
            );

            $response = [
                'msj' => true,
                'msg' => 'ok',
                'detalle' => $users1,
                'error' => $validator->errors(),
            ];

            return response($response, 200);
        }
    }

    public function ActualizarEmpleo(Request $request)
    {
        $rules = [
            'idempleo' => 'required',
            'nombre' => 'required',
            'estado' => 'required|integer',
            'pefil' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'msj' => false,
                'msg' => 'ok',
                'detalle' => 'error',
                'error' => $validator->errors(),
            ];

            return response($response, 400);
        } else {
            $idempleo = $request['idempleo'];
            $nombre = $request['nombre'];
            $estado = $request['estado'];
            $pefil = $request['pefil'];
            $personas = DB::select(
                "SELECT * FROM empleos where idempleo=' $idempleo' "
            );

            if (count($personas) > 0) {
                $update = DB::update(
                    "UPDATE empleos SET estado = '$estado', nombre = '$nombre', pefil = '$pefil'
                    WHERE idempleo = ?",
                    [$idempleo]
                );

                $json = [
                    'msj' => true,
                    'msg' => 'ok',
                    'detalle' => $update,
                    'error' => '',
                ];
                return response($json, 200);
            } else {
                $json = [
                    'msj' => true,
                    'msg' => 'ok',
                    'detalle' => 'no encotrado',
                    'error' => '',
                ];
                return response($json, 200);
            }
        }
    }
}
