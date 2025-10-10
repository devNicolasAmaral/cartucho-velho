<?php

namespace Tests; // Namespace definido no composer.json

use PHPUnit\Framework\TestCase; // Classe base do PHPUnit
use Raisa\CartuchoVelho\Calculadora; // A classe que vamos testar

class CalculadoraTest extends TestCase
{
    public function testSomaDoisNumerosPositivos(): void
    {
        // 1. Arrange (Preparar)
        $calculadora = new Calculadora();

        // 2. Act (Agir)
        $resultado = $calculadora->somar(5, 5);

        // 3. Assert (Verificar)
        $this->assertEquals(10, $resultado, "A soma de 5 + 5 deveria ser 10");
    }
}