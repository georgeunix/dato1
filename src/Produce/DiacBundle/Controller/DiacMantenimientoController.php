<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;
use Produce\DiacBundle\Util\Mantenimiento;
use Produce\DiacBundle\Util\General;
 

class DiacMantenimientoController extends Controller {

    /**
     * @Route("/especies", name="especies")
     */
    public function listEspeciesAction() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $_especies = new Mantenimiento();
            $Especies = $_especies->listEspecies($DNA);

            /* Generando el Combo de Familia-Especie */
            $Familia = $_especies->listFamilia($DNA);

            $contenido = '<select name="cbofamilia" id="cbofamilia">';
            $contenido.= '<option value="">--Seleccione--</option>';
            foreach ($Familia as $value) {
                $contenido.= '<option value="' . $value["ID"] . '">' . $value["DESCRIPCION"] . '</option>';
            }
            $contenido.= '</select>';
            /* End Generated */

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, 'especies' => $Especies, 'familia' => $contenido));

            return $this->render("DiacBundle:diac:especies.html.twig", $respuesta);
        } else {
            
        }
    }

    /**
     * @Route("/devolverEspecie", name="devolverEspecie")
     */
    public function returnEspecieAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_Especie = new Mantenimiento();
            $codEspecie = $request->request->get("id");
            $especie = $_Especie->returnEspecie($DNA, $codEspecie);

            return new Response(json_encode($especie[0]));
        }
    }
    /**
     * @Route("/devolverEspeciexfamilia", name="devolverEspeciexfamilia")
     */
    public function devolverEspeciesAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_Especie = new Mantenimiento();
            $cod_familia = $request->request->get("id");
            $especies = $_Especie->devolverEspecies($DNA, $cod_familia);
            $contenido= '';
            $contenido.= '<option value="0">--Seleccione--</option>';
            foreach ($especies as $value) {
                $contenido.= '<option value="' . $value["ID"] . '">' . $value["DESCRIPCION"] . '</option>';
            }
            $contenido.= '</select>';
            /* End Generated */
            return new Response($contenido);

        }
    }

    /**
     * @Route("/guardarEspecieNueva", name="guardarEspecieNueva")
     */
    public function guardarEspecieNuevaAction(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_Especie = new Mantenimiento();
            $esp = $request->request->get("especie");
            $cient = $request->request->get("cientifico");
            $fam = $request->request->get("familia");
            $user = $request->request->get("usuario");

            $result = $_Especie->guardarEspecie($DNA, $esp, $cient, $fam, $user);

            return new Response($result);
        }
    }

    /**
     * @Route("/actualizarEspecie", name="actualizarEspecie")
     */
    public function actualizarEspecieAction(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_Especie = new Mantenimiento();
            $id = $request->request->get("cod_especie");
            $esp = $request->request->get("especie");
            $cient = $request->request->get("cientifico");
            $fam = $request->request->get("familia");
            $user = $request->request->get("usuario");

            $result = $_Especie->actualizarEspecie($DNA, $id, $esp, $cient, $fam, $user);

            return new Response($result);
        }
    }

    /**
     * @Route("/eliminarEspecie", name="eliminarEspecie")
     */
    public function eliminarEspecieAction(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_Especie = new Mantenimiento();
            $id = $request->request->get("cod_especie");

            $result = $_Especie->eliminarEspecie($DNA, $id);

            return new Response($result);
        }
    }

    /**
     * @Route("/empresa", name="empresa")
     */
    public function listEmpresaAction() {

        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $_empresas = new Mantenimiento();
            $Empresas = $_empresas->devolverEmpresas($DNA);

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal,'empresas' => $Empresas));
            return $this->render("DiacBundle:diac:empresa.html.twig", $respuesta);
        } else {
            
        }
    }
    /**
     * @Route("/recursoh", name="recursoh")
     */
    public function listadoRecursoAction() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $_recursos = new Mantenimiento();
            $Recursos = $_recursos->devolverRecursos($DNA);
            
            /* Generando el Combo de Departamento */
            $TiposRecursos = $_recursos->devolverTiposRecursos($DNA);

            $select = '<select name="cboTipoRecurso" id="cboTipoRecurso">';
            $select.= '<option value="00">--Seleccione--</option>';
            foreach ($TiposRecursos as $value) {
                $select.= '<option value="' . $value["CODIGO_TIPO_RECURSO"] . '">' . $value["DESCRIPCION_TIPO_RECURSO"] . '</option>';
            }
            $select.= '</select>';
            /* End Generated */
            
            /* Generando el Combo de Departamento */
           $_departamentos = new General();
           $Departamentos = $_departamentos->devolverDepartamentos($DNA);

            $contenido = '<select name="cboDepartamento" id="cboDepartamento">';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($Departamentos as $value) {
                $contenido.= '<option value="' . $value["CODIGO_DEPARTAMENTO"] . '">' . $value["DEPARTAMENTO"] . '</option>';
            }
            $contenido.= '</select>';
            /* End Generated */

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal,"recursos"=>$Recursos,"departamentos"=>$contenido,"tiposrecursos"=>$select));
            
            return $this->render("DiacBundle:diac:recursoh.html.twig", $respuesta);
        } else {
            
        }
    }
    /**
     * @Route("/recursohDialog", name="recursohDialog")
     */
    public function recursohDialogAction() {
        

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $_recursos = new Mantenimiento();
            $Recursos = $_recursos->devolverRecursos($DNA);
            
            /* Generando el Combo de Departamento */
            $TiposRecursos = $_recursos->devolverTiposRecursos($DNA);

            $select = '<select name="cboTipoRecurso" id="cboTipoRecurso">';
            $select.= '<option value="00">--Seleccione--</option>';
            foreach ($TiposRecursos as $value) {
                $select.= '<option value="' . $value["CODIGO_TIPO_RECURSO"] . '">' . $value["DESCRIPCION_TIPO_RECURSO"] . '</option>';
            }
            $select.= '</select>';
            /* End Generated */
            
            /* Generando el Combo de Departamento */
           $_departamentos = new General();
           $Departamentos = $_departamentos->devolverDepartamentos($DNA);

            $contenido = '<select name="cboDepartamento" id="cboDepartamento">';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($Departamentos as $value) {
                $contenido.= '<option value="' . $value["CODIGO_DEPARTAMENTO"] . '">' . $value["DEPARTAMENTO"] . '</option>';
            }
            $contenido.= '</select>';
            /* End Generated */

            $respuesta = array_merge( array("recursos"=>$Recursos,"departamentos"=>$contenido,"tiposrecursos"=>$select));
            
            return $this->render("DiacBundle:Autorizacion:recursoshDialog.html.twig", $respuesta);

    }
    
     /**
     * @Route("/devolverRecurso", name="devolverRecurso")
     */
    public function getRecursoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $cod = $request->request->get("id");
            
            $_recurso = new Mantenimiento();
            $Recurso = $_recurso->returnRecurso($DNA, $cod);

            return new Response(json_encode($Recurso[0]));
        }
    }
    /**
     * @Route("/guardarrecursoh", name="guardarrecursoh")
     */
    public function setRecursoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            
            $codrecurso = $request->request->get("cod_recurso");
            $recurso = $request->request->get("recurso");
            $tiporecurso = $request->request->get("tiporecurso");
            $dep = $request->request->get("departamento");   
            $prov = $request->request->get("provincia");
            $dis = $request->request->get("distrito");
            $zona = $request->request->get("zona");
            $espejo = $request->request->get("espejoagua");
            $profundidad = $request->request->get("profundidad");
   
            $_recurso = new Mantenimiento();
            $Recurso = $_recurso->guardarRecurso($DNA,$codrecurso,$recurso,$tiporecurso,$dep,$prov,$dis,$zona,$espejo,$profundidad);
            return new Response($Recurso);
        }
    }
    
     /**
     * @Route("/eliminarrecursoh", name="eliminarrecursoh")
     */
    public function eliminarRecursoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $cod = $request->request->get("cod_recurso");
            
            $_recurso = new Mantenimiento();
            $Recurso = $_recurso->eliminarRecurso($DNA, $cod);

            return new Response($Recurso);
        }
    }
    
    /**
     * @Route("/anexo", name="anexo")
     */
    public function listAnexoAction() {
         $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal));
            return $this->render("DiacBundle:diac:anexo.html.twig", $respuesta);
        } else {
            
        }
    }

}

?>
