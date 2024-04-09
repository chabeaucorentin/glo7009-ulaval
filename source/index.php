<?php
/*****************************************************
 * index.php                                         *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("includes/bootstrap.php");

/*****************************************************
 *                      CONTENT                      *
 *****************************************************/
$content = '<article class="welcome">
    <h1>Projet de session</h1>
    <section>
        <p>Bienvenue sur notre plateforme dédiée à l’étude et à la correction de vulnérabilités courantes dans les
        applications web.</p>
        <p>Vous serez guidé à travers un parcours structuré afin de comprendre et mettre en place des solutions
        efficaces.</p>
        <p>Ce projet a été réalisé dans le cadre du cours de <strong>Sécurité des logiciels (GLO-7009)</strong> à l’<a
        href="https://www.ulaval.ca/" title="Université Laval" target="_blank">Université Laval</a>.</p>
    </section>
    <section>
        <h2>Objectif</h2>
        <p>Notre plateforme vise à offrir une expérience interactive centrée sur la compréhension, l’identification et
        la correction de vulnérabilités.</p>
        <p>Des failles exploitables ont été consciemment intégrées afin de servir des objectifs pédagogiques.</p>
    </section>
    <section>
        <h2>Structure</h2>
        <p>Les vulnérabilités sont traitées en suivant une structure en quatre étapes :</p>
        <div class="vulnerability">
            <ul class="navigation">
                <li>
                    <a>
                        <p><strong>Présentation</strong></p>
                        <p>Introduction de la vulnérabilité, son impact potentiel et ses objectifs.</p>
                    </a>
                </li>
                <li>
                    <a>
                        <p><strong>Démonstration</strong></p>
                        <p>Illustration de la vulnérabilité, tant dans un cadre légitime qu’abusif.</p>
                    </a>
                </li>
                <li>
                    <a>
                        <p><strong>Exploitation</strong></p>
                        <p>Explication des détails techniques de la vulnérabilité avec un code.</p>
                    </a>
                </li>
                <li>
                    <a>
                        <p><strong>Correction</strong></p>
                        <p>Révision du code et conseils pour se protéger de la vulnérabilité.</p>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section>
        <h2>Vulnérabilités</h2>
        <p>Nous avons chacun sélectionné et traité les vulnérabilités qui nous semblaient les plus pertinentes :</p>
        '.render_vulnerabilities().'
    </section>
</article>';

/*****************************************************
 *                    RENDER PAGE                    *
 *****************************************************/
echo render_page("Sécurité des logiciels", $content);
