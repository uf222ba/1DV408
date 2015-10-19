<?php

/* login.twig */
class __TwigTemplate_9ad3ae85aa4a7acab0400d394fed934c52ea939469944db0e2a14f405cb26f92 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>Dogbook | Logga in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />
        <link href=\"//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- Ionicons -->
        <link href=\"//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- Morris chart -->
        <link href=\"vendors/AdminLTE-master/css/morris/morris.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- jvectormap -->
        <link href=\"vendors/AdminLTE-master/css/jvectormap/jquery-jvectormap-1.2.2.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- Date Picker -->
        <link href=\"vendors/AdminLTE-master/css/datepicker/datepicker3.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- Daterange picker -->
        <link href=\"vendors/AdminLTE-master/css/daterangepicker/daterangepicker-bs3.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href=\"vendors/AdminLTE-master/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css\" rel=\"stylesheet\" type=\"text/css\" />
        <!-- Theme style -->
        <link href=\"vendors/AdminLTE-master/css/AdminLTE.css\" rel=\"stylesheet\" type=\"text/css\" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src=\"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js\"></script>
          <script src=\"https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js\"></script>
        <![endif]-->
    </head>
    <body class=\"skin-blue\">
        <!-- header logo: style can be found in header.less -->
        <header class=\"header\">
            <a href=\"index.html\" class=\"logo\">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Dogbook
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class=\"navbar navbar-static-top\" role=\"navigation\">
                <!-- Sidebar toggle button-->

            </nav>
        </header>

        <!-- Main content -->
        <section class=\"content\">
        <div class=\"row\">
        <!-- left column -->
        <div class=\"col-md-6\">

            ";
        // line 51
        if ((isset($context["message"]) ? $context["message"] : null)) {
            // line 52
            echo "            <!-- Felaktig inloggning -->
            <div class=\"box box-solid box-danger\">
                <div class=\"box-header\">
                    <h3 class=\"box-title\">Meddelande</h3>

                </div><!-- /.box-header -->
                <div class=\"box-body\">
                <p> ";
            // line 59
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
            echo " </p>
                </div>
            </div><!-- /.box -->
            ";
        }
        // line 63
        echo "
            <!-- general form elements -->
            <div class=\"box box-primary\">
                <div class=\"box-header\">
                    <h3 class=\"box-title\">Logga in</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role=\"form\" method=\"post\" action=\"?login\">
                    <div class=\"box-body\">
                        <div class=\"form-group\">
                            <label for=\"text\">Användarnamn</label>
                            ";
        // line 74
        if ((null === (isset($context["username"]) ? $context["username"] : null))) {
            // line 75
            echo "                                <input type=\"text\" class=\"form-control\" id=\"text\" name=\"username\" placeholder=\"Skriv in användarnamn\">
                            ";
        } else {
            // line 77
            echo "                                <input type=\"text\" class=\"form-control\" id=\"text\" name=\"username\" value=";
            echo twig_escape_filter($this->env, (isset($context["username"]) ? $context["username"] : null), "html", null, true);
            echo ">
                            ";
        }
        // line 79
        echo "                        </div>
                        <div class=\"form-group\">
                            <label for=\"exampleInputPassword1\">Lösenord</label>
                            <input type=\"password\" class=\"form-control\" id=\"exampleInputPassword1\" name=\"password\" placeholder=\"Lösenord\">
                        </div>
                        <div class=\"checkbox\">
                            <label>
                                <input type=\"checkbox\" name=\"LoginView::Checked\"> Kom ihåg mig
                            </label>
                        </div>
                    </div><!-- /.box-body -->

                    <div class=\"box-footer\">
                        <button type=\"submit\" class=\"btn btn-primary\">Logga in</button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!-- ./wrapper -->


        <script src=\"//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js\"></script>
        <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js\" type=\"text/javascript\"></script>
        <script src=\"//code.jquery.com/ui/1.11.1/jquery-ui.min.js\" type=\"text/javascript\"></script>
        <!-- Morris.js charts -->
        <script src=\"//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js\"></script>
        <script src=\"js/plugins/morris/morris.min.js\" type=\"text/javascript\"></script>
        <!-- Sparkline -->
        <script src=\"js/plugins/sparkline/jquery.sparkline.min.js\" type=\"text/javascript\"></script>
        <!-- jvectormap -->
        <script src=\"js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js\" type=\"text/javascript\"></script>
        <script src=\"js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js\" type=\"text/javascript\"></script>
        <!-- jQuery Knob Chart -->
        <script src=\"js/plugins/jqueryKnob/jquery.knob.js\" type=\"text/javascript\"></script>
        <!-- daterangepicker -->
        <script src=\"js/plugins/daterangepicker/daterangepicker.js\" type=\"text/javascript\"></script>
        <!-- datepicker -->
        <script src=\"js/plugins/datepicker/bootstrap-datepicker.js\" type=\"text/javascript\"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src=\"js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js\" type=\"text/javascript\"></script>
        <!-- iCheck -->
        <script src=\"js/plugins/iCheck/icheck.min.js\" type=\"text/javascript\"></script>

        <!-- AdminLTE App -->
        <script src=\"js/AdminLTE/app.js\" type=\"text/javascript\"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src=\"js/AdminLTE/dashboard.js\" type=\"text/javascript\"></script>

        <!-- AdminLTE for demo purposes -->
        <script src=\"js/AdminLTE/demo.js\" type=\"text/javascript\"></script>


    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 79,  108 => 77,  104 => 75,  102 => 74,  89 => 63,  82 => 59,  73 => 52,  71 => 51,  19 => 1,);
    }
}
