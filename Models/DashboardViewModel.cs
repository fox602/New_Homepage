namespace New_Homepage.Models;

public class DashboardViewModel
{
    public List<Equipment> HighlightEquipments { get; set; } = new();
    public List<Equipment> AllEquipments { get; set; } = new();
    public List<WipData> WipTrendData { get; set; } = new();
}
