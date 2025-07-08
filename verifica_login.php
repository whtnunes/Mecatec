<?php
session_start();
echo isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] ? '1' : '0';