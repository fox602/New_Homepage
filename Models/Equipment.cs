namespace New_Homepage.Models;

public class Equipment
{
    public int Id { get; set; }
    public string EquipmentId { get; set; } = string.Empty;
    public string EquipmentName { get; set; } = string.Empty;
    public EquipmentStatus Status { get; set; }
    public decimal Utilization { get; set; }
    public decimal Temperature { get; set; }
    public decimal Pressure { get; set; }
    public decimal Vacuum { get; set; }
    public int Capacity { get; set; }
    public DateTime LastUpdate { get; set; }
    public string Category { get; set; } = string.Empty;
}

public enum EquipmentStatus
{
    Online,
    Warning,
    Offline
}
