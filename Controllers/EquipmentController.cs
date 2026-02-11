using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using New_Homepage.Services;

namespace New_Homepage.Controllers;

[Authorize]
public class EquipmentController : Controller
{
    private readonly IEquipmentService _equipmentService;
    private readonly ILogger<EquipmentController> _logger;

    public EquipmentController(IEquipmentService equipmentService, ILogger<EquipmentController> logger)
    {
        _equipmentService = equipmentService;
        _logger = logger;
    }

    public async Task<IActionResult> ET12Status()
    {
        var et1Equipment = await _equipmentService.GetEquipmentsByCategoryAsync("ET1");
        var et2Equipment = await _equipmentService.GetEquipmentsByCategoryAsync("ET2");
        
        var allEquipment = et1Equipment.Concat(et2Equipment).ToList();
        
        return View(allEquipment);
    }

    public async Task<IActionResult> ET3Status()
    {
        var equipment = await _equipmentService.GetEquipmentsByCategoryAsync("ET3");
        return View(equipment);
    }
}
