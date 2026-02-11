using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using New_Homepage.Models;
using New_Homepage.Services;
using System.Diagnostics;

namespace New_Homepage.Controllers;

[Authorize]
public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;
    private readonly IEquipmentService _equipmentService;

    public HomeController(ILogger<HomeController> logger, IEquipmentService equipmentService)
    {
        _logger = logger;
        _equipmentService = equipmentService;
    }

    public async Task<IActionResult> Index()
    {
        var allEquipments = await _equipmentService.GetAllEquipmentsAsync();
        var wipData = await _equipmentService.GetWipTrendDataAsync(7);

        var viewModel = new DashboardViewModel
        {
            HighlightEquipments = allEquipments.Take(4).ToList(),
            AllEquipments = allEquipments,
            WipTrendData = wipData
        };

        return View(viewModel);
    }

    [HttpGet]
    public async Task<IActionResult> GetWipChartData()
    {
        var wipData = await _equipmentService.GetWipTrendDataAsync(7);
        
        var chartData = new
        {
            labels = wipData.Select(w => w.Date.ToString("MM/dd")).ToArray(),
            datasets = new[]
            {
                new
                {
                    label = "WIP 數量",
                    data = wipData.Select(w => w.WipCount).ToArray(),
                    borderColor = "#00a8ff",
                    backgroundColor = "rgba(0, 168, 255, 0.1)",
                    tension = 0.4
                }
            }
        };

        return Json(chartData);
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
