<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
?>

<script language="javascript">

function toggleEmployeePerformanceReview() {
        oLayer = document.getElementById("performanceLayer");
        oLink = document.getElementById("tooglePerformanceLayerLink");

        if (oLayer.style.display == 'none') {
            oLayer.style.display = 'block';
        } else {
            oLayer.style.display = 'none';
        }
        toggleEmployeePerformanceReviewText();
    }

function toggleEmployeePerformanceReviewText() {
        oLayer = document.getElementById("performanceLayer");       
        oLink = document.getElementById("tooglePerformanceLayerLink");

        if (oLayer.style.display == 'none') {       
            oLink.innerHTML = "<?php echo $lang_hremp_ShowEmployeePerformanceReview; ?>";
            oLink.className = "show";
        } else {                        
            oLink.innerHTML = "<?php echo $lang_hremp_HideEmployeePerformanceReview; ?>";   
            oLink.className = "hide";            
        }
    }

</script>

<a href="javascript:toggleEmployeePerformanceReview();" id="tooglePerformanceLayerLink"><?php echo $lang_hremp_ShowEmployeePerformanceReview; ?></a><br /><br />
<hr/>


