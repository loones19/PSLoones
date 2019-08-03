{*
This source file is subject to the Software License Agreement that is bundled with this 
package in the file license.txt, or you can get it here
http://addons-modules.com/en/content/3-terms-and-conditions-of-use

@copyright  2009-2014 Addons-Modules.com
*}

<span style="color:red;background-color:white;">
{if !empty($incompatible_agilemodules)}
	{l s='Agile Kernel has detected following incompatiable modules, please delete them and install newer version of the modules:' mod='agilekernel'}<br>
	{foreach from=$incompatible_agilemodules item=amodule}
	{$amodule}<br>
	{/foreach}
{/if}
{if !empty($incompatible_agilefiles)}
	{l s='Agile Kernel has detected following incompatiable files, please delate those files, they are not necessary be there.' mod='agilekernel'}<br>
	{foreach from=$incompatible_agilefiles item=afile}
	{$afile}<br>
	{/foreach}
{/if}
</span>

